<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\DeliveryZone;
use App\Models\District;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use ImranDev\UniversalSlug\Models\SlugHistory;

class ProductLandingController extends Controller
{
    /**
     * Display product landing page or 301 redirect historical slug.
     */
    public function show(Request $request, string $slug): View|RedirectResponse
    {
        // 1. Guard reserved system routes
        if (slug_is_reserved($slug)) {
            abort(404);
        }

        // 2. Find product by current active slug
        $product = Product::where('slug', $slug)
            ->active()
            ->with(['images', 'variants', 'offers'])
            ->first();

        // 3. Check for 301 redirect in slug history if not found
        if (!$product) {
            $history = SlugHistory::where('slug', $slug)
                ->where('sluggable_type', Product::class)
                ->latest()
                ->first();

            if ($history && $history->sluggable) {
                return redirect()->to('/' . $history->sluggable->slug, 301);
            }

            abort(404, 'Product not found.');
        }

        // 4. Load form options
        $districts = District::orderBy('name_en')->get(['id', 'name_en', 'name_bn', 'is_inside_dhaka', 'is_sub_dhaka']);
        $deliveryZones = DeliveryZone::where('is_active', true)->orderBy('charge')->get();

        return view('landing.product', compact('product', 'districts', 'deliveryZones'));
    }
}
