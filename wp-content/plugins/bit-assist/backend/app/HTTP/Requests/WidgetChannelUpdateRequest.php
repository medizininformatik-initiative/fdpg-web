<?php

namespace BitApps\Assist\HTTP\Requests;

use BitApps\Assist\Core\Http\Request\Request;

class WidgetChannelUpdateRequest extends Request
{
    public function rules()
    {
        return [
            'widget_id'    => ['required'],
            'channel_name' => ['required'],
            'config'       => ['required'],
            'sequence'     => ['nullable'],
        ];
    }
}
