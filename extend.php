<?php

/*
 * This file is part of Zen3D/STL-viewer.
 *
 * Copyright (c) 2022 Billy Wilcosky.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace zen3d\stlviewer;

use Flarum\Extend;
use s9e\TextFormatter\Configurator;

use Flarum\Foundation\AbstractServiceProvider;
use FoF\Upload\Contracts\Template;
use FoF\Upload\File;
use FoF\Upload\Helpers\Util;

class MyTemplate implements Template
{
    public function tag(): string
    {
        return 'stlviewerbbcode';
    }

    public function name(): string
    {
        return 'STL viewer bbcode';
    }

    public function description(): string
    {
        return 'A description of the template that appears in the admin panel';
    }

    public function preview(File $file): string
    {
        return '[stl-file name='.$file->base_name.' url='.$file->url.' uuid='.$file->uuid.' size='.$file->humanSize.']';
    }
}

class MyServiceProvider extends AbstractServiceProvider
{
    public function register()
    {
        $this->container->make(Util::class)->addRenderTemplate($this->container->make(MyTemplate::class));
    }
}

return [ 
  (new Extend\Frontend('forum'))
  ->css(__DIR__.'/less/forum.less'),
    (new Extend\Formatter)
    ->configure(function (Configurator $config) {
         $config->BBCodes->addCustom(
          '[stl-file name={SIMPLETEXT1} url={URL} uuid={IDENTIFIER} size={SIMPLETEXT2}]',
           '<div class="stliframe" style="--aspect-ratio: 16/9;">
           <iframe 
             src="/stl-viewer/?file={URL}"
             width="1600"
             height="900"
             frameborder="0"
           >
           </iframe>
		   <div class="StlButtonGroup" data-fof-upload-download-uuid="{IDENTIFIER}">
			<div class="Button hasIcon Button--icon Button--primary"><i class="fas fa-download"></i></div>
			<div class="Button">{SIMPLETEXT1}</div>
			<div class="Button">{SIMPLETEXT2}</div>
		   </div>
         </div>'
        );
    }),
    (new Extend\ServiceProvider())
        ->register(MyServiceProvider::class)
];