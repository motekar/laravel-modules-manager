<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuleController extends Controller
{
    const CORE_MODULES = ['Core', 'Common'];

    public function modules()
    {
        $modules = collect(app('modules')->all())->transform(function ($module) {
            /** @var \Nwidart\Modules\Module $module */
            return [
                'name' => $module->getName(),
                'description' => $module->getDescription(),
                'enabled' => $module->isEnabled(),
                'priority' => $module->get('priority'),
                'path' => $module->getPath(),
                'is_core' => in_array($module->getName(), static::CORE_MODULES),
                'requires' => $module->getRequires(),
            ];
        })->sortBy([
            ['is_core', 'desc'],
            ['name', 'asc'],
        ]);

        return view('modules', compact('modules'));
    }

    public function updateModule(Request $request)
    {
        $request->validate([
            'action' => ['required', 'in:enable,disable'],
            'module' => ['required', 'string', Rule::notIn(static::CORE_MODULES)],
        ]);

        $name = $request->get('module');

        /** @var \Nwidart\Modules\Module $module */
        $module = app('modules')->findOrFail($name);

        switch ($request->action) {
            case 'enable':
                // check dependencies
                $deps = $module->getRequires();
                foreach ($deps as $dep) {
                    /** @var \Nwidart\Modules\Module $m */
                    $m = app('modules')->find($dep);
                    if (!$m || $m->isDisabled()) {
                        return redirect()->back()
                            ->with('error', sprintf('Unable to enable <strong>%s</strong> module. <strong>%s</strong> module is required.', $name, $dep));
                    }
                }

                if ($module->isDisabled()) $module->enable();
                break;
            case 'disable':
                // check dependencies
                $modules = app('modules')->getByStatus(1);
                /** @var \Nwidart\Modules\Module $m */
                foreach ($modules as $m) {
                    $deps = $m->getRequires();
                    if (in_array($name, $deps)) {
                        return redirect()->back()
                            ->with('error', sprintf('Unable to disable <strong>%s</strong> module. Required by <strong>%s</strong> module.', $name, $m->getName()));
                    }
                }

                if ($module->isEnabled()) $module->disable();
                break;
        }

        return redirect()->back();
    }
}
