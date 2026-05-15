<?php

namespace App\Helper;
use App\Models\MasterMenu;
use App\Models\Level1Menu;
use App\Models\Level2Menu;
use App\Models\Level3Menu;
use Illuminate\Support\Facades\Auth;

class Menu
{
    public function getMenu($kd_menu){

        $menu = [];
        // $kd_service_apps = [$kd_menu,"ALL"];
        $dataMasterMenu = MasterMenu::where('master_menu.kd_service_apps',$kd_menu)->where('master_menu.active',"t")->orderBy('master_menu.created_at','asc')->select("master_menu.link");
        if(Auth::user()->is_admin == "t"){
            $tipe_menu = ["all","admin"];
            $dataMasterMenu->whereIn('master_menu.tipe_menu',$tipe_menu);
        }else{
            $tipe_menu = ["all"];
            $dataMasterMenu->whereIn('master_menu.tipe_menu',$tipe_menu)->join("public.user_menu","user_menu.id_menu","master_menu.id");
        }
        $masterMenu = [];
        foreach($dataMasterMenu->get() as $value){
            $masterMenu[] = $value->link;
        }

        $menu1 = Level1Menu::where('kd_service_apps',$kd_menu)->where('active',"t")->select('kd_level1_menu','link','icon','nama_menu','created_at')->whereIn('link',$masterMenu)->orderBy('kd_level1_menu')->get();

        $menu2 = Level2Menu::where('kd_service_apps',$kd_menu)->where('active',"t")->select('kd_level1_menu','kd_level2_menu','link','nama_menu','created_at')->whereIn('link',$masterMenu)->with('level1menu')->get();

        $menu3 = Level3Menu::where('kd_service_apps',$kd_menu)->where('active',"t")->select('kd_level2_menu','kd_level3_menu','link','nama_menu','created_at')->whereIn('link',$masterMenu)->with('level2menu.level1menu')->get();
        
        foreach($menu1 as $value){
            // return $value->nama_menu;
            $menu[$value->kd_level1_menu] = [
                "link"=>$value->link,
                "icon"=>$value->icon,
                "nama_menu"=>$value->nama_menu,
                "level2_menu"=>null,
                "created_at"=>$value->created_at
            ];
        }
        foreach($menu2 as $value){
            if($value->level1menu != null){
                if(array_key_exists($value->level1menu->kd_level1_menu,$menu)){
                    $menu[$value->level1menu->kd_level1_menu]["level2_menu"][$value->kd_level2_menu] = [
                        "link"=>$value->link,
                        "nama_menu"=>$value->nama_menu,
                        "level3_menu"=>null,
                        "created_at"=>$value->created_at
                    ];
                }else{
                    $menu[$value->level1menu->kd_level1_menu] = [
                        "link"=>$value->level1menu->link,
                        "icon"=>$value->level1menu->icon,
                        "nama_menu"=>$value->level1menu->nama_menu,
                        "level2_menu"=>[$value->kd_level2_menu => [
                            "link"=>$value->link,
                            "nama_menu"=>$value->nama_menu,
                            "level3_menu"=>null,
                            "created_at"=>$value->created_at
                            ] 
                        ],
                        "created_at"=>$value->level1menu->created_at
                    ];
                }
                
            }
        }

        foreach($menu3 as $value){
           if($value->level2menu != null){
                if($value->level2menu->level1menu != null){
                    if(array_key_exists($value->level2menu->level1menu->kd_level1_menu,$menu)){
                        if(array_key_exists($value->level2menu->kd_level2_menu,$menu[$value->level2menu->level1menu->kd_level1_menu]["level2_menu"])){
                            $menu[$value->level2menu->level1menu->kd_level1_menu]["level2_menu"][$value->level2menu->kd_level2_menu]["level3_menu"][$value->kd_level3_menu] = 
                                [
                                    "link"=>$value->link,
                                    "nama_menu"=>$value->nama_menu,
                                    "created_at"=>$value->created_at
                                ];
                        }else{
                            $menu[$value->level2menu->level1menu->kd_level1_menu]["level2_menu"][$value->level2menu->kd_level2_menu] = [
                                "link"=>$value->level2menu->link,
                                "nama_menu"=>$value->level2menu->nama_menu,
                                "level3_menu"=>[$value->kd_level3_menu => 
                                            [
                                                "link"=>$value->link,
                                                "nama_menu"=>$value->nama_menu,
                                                "created_at"=>$value->created_at
                                            ]
                                        ],
                                "created_at"=>$value->level2menu->created_at
                            ];
                        }
                    }else{
                        $menu[$value->level2menu->level1menu->kd_level1_menu] = [
                            "link"=>$value->level2menu->level1menu->link,
                            "icon"=>$value->level2menu->level1menu->icon,
                            "nama_menu"=>$value->level2menu->level1menu->nama_menu,
                            "level2_menu"=>[$value->level2menu->kd_level2_menu => [
                                "link"=>$value->level2menu->link,
                                "nama_menu"=>$value->level2menu->nama_menu,
                                "level3_menu"=>[$value->kd_level3_menu => 
                                        [
                                            "link"=>$value->link,
                                            "nama_menu"=>$value->nama_menu,
                                            "created_at"=>$value->created_at
                                        ]
                                        ],
                                "created_at"=>$value->level2menu->created_at
                                ]    
                            ],
                            "created_at"=>$value->level2menu->level1menu->created_at
                        ];
                    }
                }
            } 
        }
        
        return $menu;
    }
}