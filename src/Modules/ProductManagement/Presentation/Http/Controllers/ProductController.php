<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Modules\ProductManagement\Application\Validators\ProductRequestValidator;
use Modules\Shared\Application\Validators\ValidationException;
use Ramsey\Uuid\Uuid;
use Modules\ProductManagement\Infrastructure\Bus\CommandBus;
use Modules\ProductManagement\Infrastructure\Bus\QueryBus;
use Modules\ProductManagement\Application\Queries\GetAllProducts\GetAllProductsQuery;
use Modules\ProductManagement\Application\Queries\GetProduct\GetProductQuery;
use Modules\ProductManagement\Application\Commands\CreateProduct\CreateProductCommand;
use Modules\ProductManagement\Application\Commands\DeleteProduct\DeleteProductCommand;
use Modules\ProductManagement\Application\Commands\UpdateProduct\UpdateProductCommand;
use Modules\ProductManagement\Domain\Entities\Product;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRequestValidator $validator,
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus
    ) {}

    public function index()
    {
        try {
            $products = $this->queryBus->ask(new GetAllProductsQuery());
            return view('products.index', compact('products'));
        } catch (\Throwable $e) {
            return redirect()->route('products.index')->with('error', "Error fetching products");
        }
    }

    public function show(string $id)
    {
        try {
            $product = $this->getProduct($id);
            return view('products.show', compact('product'));
        } catch (\Throwable $e) {
            return redirect()->route('products.index')->with('error', "Product[{$id}] not found");
        }
    }

    public function store(Request $request)
    {
        try {
            $requestProductDTO = $this->validator->validate()->getValidatedDTO();
            $this->commandBus->dispatch(new CreateProductCommand($requestProductDTO));
            return redirect()->route('products.index')
                ->with('success', 'Product created successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->getErrors());
        } catch (\Throwable $e) {
            return redirect()->route('products.index')->with('error', "Product could not be created");
        }
    }

    public function create()
    {
        return view('products.create');
    }

    public function update(Request $request, string $id)
    {
        try {
            $requestProductDTO = $this->validator->validate()->getValidatedDTO();
            $this->commandBus->dispatch(new UpdateProductCommand($requestProductDTO));
            return redirect()->route('products.index')->with('success', "Product[{$id}] updated successfully");
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->getErrors());
        } catch (\Throwable $e) {
            return redirect()->route('products.index')->with('error', "Product[{$id}] could not be updated");
        }
    }

    public function edit(string $id)
    {
        $product = $this->getProduct($id);
        return view('products.edit', compact('product'));
    }

    public function destroy(string $id)
    {
        try {
            $this->getProduct($id);
            $this->commandBus->dispatch(new DeleteProductCommand(Uuid::fromString($id)));
            return redirect()->route('products.index')->with('success', "Product[{$id}] deleted successfully");
        } catch (\Throwable $e) {
            return redirect()->route('products.index')->with('error', "Product[{$id}] could not be deleted");
        }
    }

    private function getProduct(string $id): Product
    {
        try {
            $product = $this->queryBus->ask(new GetProductQuery(Uuid::fromString($id)));
        } catch (\Throwable $e) {
            abort(404);
        }

        if (!$product) {
            abort(404);
        }

        return $product;
    }
}
