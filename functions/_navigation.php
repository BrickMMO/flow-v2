<?php

function navigation_array($selected = false)
{

    $navigation = [
        [
            'title' => 'Flow',
            'sections' => [
                [
                    'title' => 'Flow',
                    'id' => 'flow',
                    'pages' => [
                        [
                            'icon' => 'bm-flow',
                            'url' => '/console/calendar',
                            'title' => 'Flow',
                            'sub-pages' => [
                                [
                                    'title' => 'Calendar',
                                    'url' => '/console/calendar',
                                    'colour' => 'red',
                                ],[
                                    'title' => 'Recent Timesheet Entries',
                                    'url' => '/console/recent',
                                    'colour' => 'red',
                                ],[
                                    'title' => 'Add Timesheet Entry',
                                    'url' => '/console/add',
                                    'colour' => 'red',
                                ],[
                                    'br' => '---',
                                ],[
                                    'title' => 'Visit Flow App',
                                    'url' => 'https://flow.brickmmo.com',
                                    'colour' => 'orange',
                                    'icon' => 'fa-solid fa-arrow-up-right-from-square',
                                ],[
                                    'br' => '---',
                                ],[
                                    'title' => 'Uptime Report',
                                    'url' => 'https://uptime.brickmmo.com/details/10',
                                    'colour' => 'orange',
                                    'icons' => 'bm-uptime',
                                ],[
                                    'title' => 'Stats Report',
                                    'url' => '/stats/qr',
                                    'colour' => 'orange',
                                    'icons' => 'bm-stats',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    if($selected)
    {
        
        $selected = '/'.$selected;
        $selected = str_replace('//', '/', $selected);
        $selected = str_replace('.php', '', $selected);
        $selected = str_replace('.', '/', $selected);
        $selected = substr($selected, 0, strrpos($selected, '/'));

        foreach($navigation as $levels)
        {

            foreach($levels['sections'] as $section)
            {

                foreach($section['pages'] as $page)
                {

                    if(strpos($page['url'], $selected) === 0)
                    {
                        return $page;
                    }

                }

            }

        }

    }

    return $navigation;

}