<?php

namespace BitApps\AssistPro\HTTP\Controllers;

use BitApps\Assist\Config;
use BitApps\AssistPro\Core\Http\Response;
use BitApps\AssistPro\Core\Http\Request\Request;
use BitApps\AssistPro\HTTP\Requests\WidgetUpdateRequest;
use BitApps\AssistPro\Model\Widget;

final class WidgetController
{
    public function update(WidgetUpdateRequest $request, Widget $widget)
    {
        $widget->update($request->all());

        if ($widget->save()) {
            return Response::success('Widget updated');
        }
        return  Response::error('Widget update failed');
    }

    public function changeActive(Request $request, $widgetId)
    {
        $activeWidget = Widget::where('active', 1)->where('id', '!=', $widgetId)->first();
        if (isset($activeWidget->id)) {
            $activeWidget->update(['active' => 0])->save();
        }

        $widget = Widget::where('id', $widgetId)->first();
        $widget->update(['active' => $request->active]);

        if ($widget->save()) {
            Config::updateOption('widget_active', (($request->active && $widget->status) ? $widget->id : null));
            if ($request->active) {
                return Response::success('Widget activated');
            }
            return Response::success('Widget deactivated');
        }
        return Response::error('Something went wrong');
    }
}
