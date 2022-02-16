<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Required route files
     *
     * //
     *
     * @var array
     */
    private $routeFiles = [
        'routes/api.php',
        'routes/admin/usuario.php',
        'routes/admin/grupoUsuario.php',
        'routes/admin/funcaoUsuario.php',
        'routes/auth/auth.php',
        'routes/user/user.php',
        'routes/cadastros/cadastro.php',
        'routes/cadastros/cidade.php',
        'routes/cadastros/cliente.php',
        'routes/cadastros/endereco.php',
        'routes/cadastros/empresa.php',
        'routes/cadastros/transportador.php',
        'routes/cadastros/parceiro.php',
        'routes/cadastros/pessoal.php',
        'routes/cadastros/email.php',
        'routes/cadastros/tiposEmail.php',
        'routes/cadastros/classeemail.php',
        'routes/cadastros/cadastroClasseEmail.php',
        'routes/cadastros/fornecedor.php',
        'routes/financeiro/serie.php',
        'routes/financeiro/portador.php',
        'routes/venda/condicaoVenda.php',
        'routes/materiais/produto.php',
        'routes/materiais/fabricante.php',
        'routes/materiais/familia.php',
        'routes/materiais/fichaTecnica.php',
        'routes/materiais/fichaTecnicaValor.php',
        'routes/materiais/categoria.php',
        'routes/materiais/deposito.php',
        'routes/materiais/prazoProduto.php',
        'routes/materiais/precoProduto.php',
        'routes/materiais/tabelaPreco.php',
        'routes/materiais/tipoMovimento.php',
        'routes/materiais/movimentoEstoque.php',
        'routes/materiais/depositoUsuarios.php',
        'routes/materiais/transEstoque.php',
        'routes/materiais/transformaProduto.php',
        'routes/materiais/produtoComposicao.php',
        'routes/logistica/tipoTransporte.php',
        'routes/logistica/tabelaFrete.php',
        'routes/logistica/arquivoFrete.php',
        'routes/logistica/etiquetaTransporte.php',
        'routes/materiais/produtoncm.php',
        'routes/customs/estadosdinamico.php',
        'routes/customs/dynamic.php',
        'routes/customs/scaffold.php',
        //APEND_ROUTE
    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(function () {
                $this->bootRoutes();
            });
        });
    }

    /**
     * Initialize all routes
     *
     * @return void
     */
    private function bootRoutes()
    {
        $routes = $this->routeFiles;

        foreach($routes as $route){
            require base_path($route);
        }
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
