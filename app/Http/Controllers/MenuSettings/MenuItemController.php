<?php

namespace App\Http\Controllers\MenuSettings;

use App\Http\Controllers\Controller;
use App\Models\Href;
use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $category = MenuCategory::findOrFail($request->menu);
            $title = $category->name . ' ' . __('all items');
            $hrefs = Href::all();

            return view('backend.pages.settings.menu-settings.menu-item.index', compact('title', 'category', 'hrefs'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $category = MenuCategory::findOrFail($request->menu);
            $items = Menu::where(['parent_id' => null, 'category_id' => $category->id])
                ->select('id', 'href', 'icon', 'text', 'target', 'title')
                ->orderBy('position', 'ASC')
                ->get();
            foreach ($items as $key => $item) {
                $aItems = Menu::where(['parent_id' => $item->id, 'category_id' => $category->id])
                    ->select('id', 'href', 'icon', 'text', 'target', 'title')
                    ->orderBy('position', 'ASC')
                    ->get();
                if (count($aItems) > 0) {
                    foreach ($aItems as $akey => $achild) {
                        $bItems = Menu::where(['parent_id' => $achild->id, 'category_id' => $category->id])
                            ->select('id', 'href', 'icon', 'text', 'target', 'title')
                            ->orderBy('position', 'ASC')
                            ->get();
                        if (count($bItems) > 0) {
                            foreach ($bItems as $bKey => $bChild) {
                                $cItems = Menu::where(['parent_id' => $bChild->id, 'category_id' => $category->id])
                                    ->select('id', 'href', 'icon', 'text', 'target', 'title')
                                    ->orderBy('position', 'ASC')
                                    ->get();
                                if (count($cItems) > 0) {
                                    foreach ($cItems as $ckey => $cChild) {
                                        $dItems = Menu::where(['parent_id' => $cItems->id, 'category_id' => $category->id])
                                            ->select('id', 'href', 'icon', 'text', 'target', 'title')
                                            ->orderBy('position', 'ASC')
                                            ->get();
                                        if (count($dItems) > 0) {
                                            $cItems[$ckey]->children = $dItems;
                                        }
                                    }
                                    $bItems[$bKey]->children = $cItems;
                                }
                            }
                            $aItems[$akey]->children = $bItems;
                        }
                    }
                    $items[$key]->children = $aItems;
                }
            }
            $data = json_encode($items);
            return response()->json($data);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $data = (object)[
//            'alert_type' => 'success',
//            'message' => route('demo-protection')
//        ];
//        return response()->json($data);
//
        try {
            $category = MenuCategory::findOrFail($request->menu);
            $category->menus->each->delete();
            $items = json_decode($request->items, true);
            foreach ($items as $key => $row) {
                $row = (object)$row;
                $mainMenu = new Menu();
                $mainMenu->category_id = $category->id;
                $mainMenu->text = $row->text;
                $mainMenu->icon = $row->icon;
                $mainMenu->href = $row->href;
                $mainMenu->target = $row->target;
                $mainMenu->title = property_exists($row, 'title') ? $row->title : null;
                $mainMenu->parent_id = null;
                $mainMenu->position = $key + 1;
                $mainMenu->save();
                if (property_exists($row, 'children')) {
                    foreach ($row->children as $cKey => $child) {
                        $child = (object)$child;
                        $menuChild = new Menu();
                        $menuChild->category_id = $category->id;
                        $menuChild->text = $child->text;
                        $menuChild->icon = $child->icon;
                        $menuChild->href = $child->href;
                        $menuChild->target = $child->target;
                        $menuChild->title = property_exists($child, 'title') ? $child->title : null;
                        $menuChild->parent_id = $mainMenu->id;
                        $menuChild->position = $cKey + 1;
                        $menuChild->save();
                        if (property_exists($child, 'children')) {
                            foreach ($child->children as $c2Key => $child2) {
                                $child2 = (object)$child2;
                                $childMenuChild = new Menu();
                                $childMenuChild->category_id = $category->id;
                                $childMenuChild->text = $child2->text;
                                $childMenuChild->icon = $child2->icon;
                                $childMenuChild->href = $child2->href;
                                $childMenuChild->target = $child2->target;
                                $childMenuChild->title = property_exists($child2, 'title') ? $child2->title : null;
                                $childMenuChild->parent_id = $menuChild->id;
                                $childMenuChild->position = $c2Key + 1;
                                $childMenuChild->save();
                                if (property_exists($child2, 'children')) {
                                    foreach ($child2->children as $c3Key => $child3) {
                                        $child3 = (object)$child3;
                                        $childMenuChild1 = new Menu();
                                        $childMenuChild1->category_id = $category->id;
                                        $childMenuChild1->text = $child3->text;
                                        $childMenuChild1->icon = $child3->icon;
                                        $childMenuChild1->href = $child3->href;
                                        $childMenuChild1->target = $child3->target;
                                        $childMenuChild1->title = property_exists($child3, 'title') ? $child3->title : null;
                                        $childMenuChild1->parent_id = $childMenuChild->id;
                                        $childMenuChild1->position = $c3Key + 1;
                                        $childMenuChild1->save();
                                        if (property_exists($child3, 'children')) {
                                            foreach ($child3->children as $c4Key => $child4) {
                                                $child4 = (object)$child4;
                                                $childMenuChild2 = new Menu();
                                                $childMenuChild2->category_id = $category->id;
                                                $childMenuChild2->text = $child4->text;
                                                $childMenuChild2->icon = $child4->icon;
                                                $childMenuChild2->href = $child4->href;
                                                $childMenuChild2->target = $child4->target;
                                                $childMenuChild2->title = property_exists($child4, 'title') ? $child4->title : null;
                                                $childMenuChild2->parent_id = $childMenuChild1->id;
                                                $childMenuChild2->position = $c4Key + 1;
                                                $childMenuChild2->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $data = (object)[
                'alert_type' => 'success',
                'message' => 'All menu set successfully'
            ];

            return response()->json($data);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
