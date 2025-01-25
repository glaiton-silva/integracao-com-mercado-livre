<?php
namespace App\Http\Controllers;

use App\Models\{Token, Attribute};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Http, Log};

class MercadoLivreController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        $this->clientId = env('MERCADO_LIVRE_CLIENT_ID');
        $this->clientSecret = env('MERCADO_LIVRE_CLIENT_SECRET');
        $this->redirectUri = env('MERCADO_LIVRE_REDIRECT_URI');
    }

    /**
     * Gerar URL de autenticação.
     */
    public function getAuthorizationUrl()
    {
        $codeVerifier = $this->generateCodeVerifier();
        $codeChallenge = $this->generateCodeChallenge($codeVerifier);
        
        session(['code_verifier' => $codeVerifier]);

        $url = 'https://auth.mercadolivre.com.br/authorization';

        return redirect("$url?response_type=code&client_id=$this->clientId&redirect_uri=$this->redirectUri&code_challenge=$codeChallenge&code_challenge_method=S256");
    }

    /**
     * Callback para salvar o code no banco.
     */
    public function callback(Request $request)
    {
        $code = $request->get('code');

        if (!$code) {
            return response()->json(['error' => 'Code não fornecido.'], 400);
        }

        Token::updateOrCreate([], ['code' => $code]);

        return $this->getAccessToken($code);
    }

    /**
     * Obter token de acesso válido ou renová-lo automaticamente.
     */
    public function getAccessToken()
    {
        $token = Token::latest()->first();

        if ($token && $token->isValid()) {
            return $token->access_token;
        }

        if ($token && $token->refresh_token) {
            return $this->refreshToken($token);
        }

        if ($token && $token->code) {
            return $this->generateAccessToken($token->code);
        }

        return response()->json(['error' => 'Code ou refresh token ausente.'], 400);
    }

    /**
     * Gerar access token usando o authorization code.
     */
    private function generateAccessToken(string $code)
    {

        $codeVerifier = session('code_verifier');
        if (!$codeVerifier) {
            return response()->json(['error' => 'Code verifier não encontrado na sessão.'], 400);
        }

        $response = Http::post('https://api.mercadolibre.com/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'code_verifier' => $codeVerifier,
        ]);

        if ($response->successful()) {
            $this->saveToken($response->json());
        }

        return response()->json(['success' => 'Token gerado com sucesso!'], 200);
    }

    /**
     * Renovar o token de acesso usando refresh token.
     */
    private function refreshToken(Token $token)
    {
        $response = Http::post('https://api.mercadolibre.com/oauth/token', [
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $token->refresh_token,
        ]);

        if ($response->successful()) {
            return $this->saveToken($response->json());
        }

        Log::error('Erro ao renovar token: ' . $response->body());
        return null;
    }

    /**
     * Salvar ou atualizar o token no banco.
     */
    private function saveToken(array $data)
    {
        $token = Token::updateOrCreate(
            [],
            [
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? null,
                'expires_at' => now()->addSeconds($data['expires_in']),
            ]
        );

        return $token->access_token;
    }

    /**
     * Buscar categorias no Mercado Livre.
     */
    public function getCategories()
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return response()->json(['error' => 'Token inválido.'], 400);
        }

        $response = Http::withToken($accessToken)->get('https://api.mercadolibre.com/sites/MLB/categories');

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Erro ao buscar categorias: ' . $response->body());
        return response()->json([], 500);
    }

    /**
     * Buscar atributos de uma categoria específica.
     */
    public function getCategoryAttributes(string $categoryId)
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return response()->json(['error' => 'Token inválido.'], 400);
        }

        $response = Http::withToken($accessToken)->get("https://api.mercadolibre.com/categories/{$categoryId}/attributes");

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("Erro ao buscar atributos da categoria {$categoryId}: " . $response->body());
        return response()->json([], 500);
    }

    /**
     * Criar ou atualizar um produto.
     */
    public function createOrUpdateProduct(array $productData, ?string $productId = null)
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return response()->json(['error' => 'Token inválido.'], 400);
        }

        if ($productId) {
            if (empty($productData)) {
                $url = "https://api.mercadolibre.com/items/{$productId}";
                $response = Http::withToken($accessToken)->delete($url);
                if ($response->successful()) {
                    return response()->json(['message' => 'Produto excluído com sucesso!']);
                }
                Log::error('Erro ao excluir produto: ' . $response->body());
                return response()->json(['error' => 'Erro ao excluir produto.'], 500);
            } else {
                $url = "https://api.mercadolibre.com/items/{$productId}";
                $productData['listing_type_id'] = $productData['listing_type_id'] ?? 'free';
                $response = Http::withToken($accessToken)->put($url, $productData);
            }
        } else {
            $url = 'https://api.mercadolibre.com/items';
            $productData['listing_type_id'] = $productData['listing_type_id'] ?? 'free';
            $response = Http::withToken($accessToken)->post($url, $productData);
        }
        
        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Erro ao criar/atualizar produto: ' . $response->body());
        return response()->json([], 500);
    }

    /**
     * Buscar e salvar atributos de uma categoria.
     */
    public function fetchAndSaveCategoryAttributes(string $categoryId)
    {
        $attributes = $this->getCategoryAttributes($categoryId);

        if (empty($attributes)) {
            Log::warning("Nenhum atributo encontrado para a categoria {$categoryId}.");
            return;
        }

        foreach ($attributes as $attribute) {
            Attribute::updateOrCreate(
                [
                    'category_id' => $categoryId,
                    'attribute_id' => $attribute['id']
                ],
                [
                    'name' => $attribute['name'],
                    'value_type' => $attribute['value_type'],
                    'tags' => $attribute['tags'] ?? [],
                    'values' => $attribute['values'] ?? [],
                ]
            );
        }

        Log::info("Atributos da categoria {$categoryId} foram salvos/atualizados com sucesso.");
    }

    function generateCodeVerifier(): string
    {
        return bin2hex(random_bytes(64));
    }

    function generateCodeChallenge(string $codeVerifier): string
    {
        return rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
    }

}
