<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Product data (could be moved to a model/service later)
     */
    private function getProductData()
    {
        return [
            'feedstocks' => [
                'key' => 'feedstocks',
                'slug' => 'feedstocks',
                'title' => 'FEEDSTOCKS',
                'description' => "Feedstock refers to the primary raw materials used in the production of energy, particularly renewable and bio-based fuels. As an energy company specializing in feedstock supply, we provide sustainable materials sourced from renewable resources, waste, and industrial residues.\n\nOur feedstocks meet strict quality, traceability, and sustainability standards, supported by integrated logistics and reliable supply networks to ensure consistent delivery and support the global energy transition.",
                'products' => [
                    ['name' => 'POME / RPOME', 'desc' => 'Palm Oil Mill Effluent – a byproduct of palm oil processing used as renewable feedstock.'],
                    ['name' => 'UCO / RUCO', 'desc' => 'Used Cooking Oil – recycled cooking oil collected for biodiesel production.'],
                    ['name' => 'EFBO', 'desc' => 'Empty Fruit Bunch Oil – extracted from palm empty fruit bunches.'],
                    ['name' => 'SBEO', 'desc' => 'Soybean Oil – plant-based oil from soybean processing.'],
                    ['name' => 'Trans-esterified Residue', 'desc' => 'Residue generated from biodiesel transesterification process.'],
                ],
            ],
            'methyl' => [
                'key' => 'methyl',
                'slug' => 'waste-based-fame',
                'title' => 'WASTE-BASED FAME',
                'description' => "Waste-based FAME (Fatty Acid Methyl Ester) is a renewable biodiesel produced from used cooking oils, processing residues, and other waste-based feedstocks. It delivers greenhouse-gas savings by diverting waste streams into circular, lower-carbon energy.\n\nOur waste-based FAME products meet international quality and sustainability standards and are compatible with existing diesel engines and infrastructure, making them ideal for reducing carbon emissions in transportation and industrial applications.",
                'products' => [
                    ['name' => 'POME-ME', 'desc' => 'Biodiesel made from palm oil mill wastewater.'],
                    ['name' => 'UCO-ME', 'desc' => 'Biodiesel produced from recycled used cooking oil.'],
                    ['name' => 'TER-ME', 'desc' => 'Biodiesel derived from industrial oil and fat residues.'],
                ],
            ],
            'others' => [
                'key' => 'others',
                'slug' => 'others',
                'title' => 'OTHERS',
                'description' => "Beyond our core feedstock and waste-based FAME offerings, we supply a range of complementary products derived from the biofuel production process. These byproducts and derivatives support various industrial applications.\n\nOur diversified product portfolio enables us to maximize resource utilization while providing valuable materials to industries including cosmetics, pharmaceuticals, and chemical manufacturing.",
                'products' => [
                    ['name' => 'CRUDE GLYCERIN', 'desc' => 'Byproduct of biodiesel production used in cosmetics and pharmaceuticals.'],
                    ['name' => 'PF-AD', 'desc' => 'Essential components for soap, detergent, and chemical industries.'],
                ],
            ],
        ];
    }

    /**
     * Display the products catalog (index page)
     */
    public function index()
    {
        return view('pages.products.index');
    }

    /**
     * Display a specific product category detail page
     */
    public function show(Request $request, $category)
    {
        $productData = $this->getProductData();
        
        // Map slug to key
        $slugToKey = [
            'feedstocks' => 'feedstocks',
            'waste-based-fame' => 'methyl',
            // Legacy slug support
            'methyl-ester' => 'methyl',
            'others' => 'others',
        ];
        
        $categoryKey = $slugToKey[$category] ?? null;
        
        if (!$categoryKey || !isset($productData[$categoryKey])) {
            abort(404, 'Product category not found');
        }
        
        $data = $productData[$categoryKey];
        $allCategories = $productData;
        
        // Check if this is a partial request (AJAX)
        // Only check for X-Partial header to avoid false positives
        if ($request->header('X-Partial') === 'product-detail') {
            // Return only the detail partial
            return view('pages.products.partials.detail', [
                'categoryKey' => $categoryKey,
                'categoryTitle' => $data['title'],
                'categoryDescription' => $data['description'],
                'products' => $data['products'],
            ]);
        }
        
        // Return full page
        return view('pages.products.show', [
            'categoryKey' => $categoryKey,
            'categoryTitle' => $data['title'],
            'categoryDescription' => $data['description'],
            'products' => $data['products'],
            'allCategories' => $allCategories,
        ]);
    }
}
