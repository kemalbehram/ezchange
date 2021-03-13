<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigurationController extends Controller
{
    public function index(): array
    {
        $configs = Configuration::all();
        if ($configs->isNotEmpty())
        {
            return ['status' => 'success', 'message' => 'Configs List', 'data' => $configs];
        } else {
            return ['status' => 'error', 'message' => 'No configs exists at the moment', 'data' => null];
        }
    }

    public function store(Request $request):array
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|alpha_dash|unique:configurations,title',
            'value' => 'required|string',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $tmp = Configuration::create([
            'title' => \request('title'),
            'value' => \request('value')
        ]);
        if ($tmp)
        {
            return ['status' => 'success', 'message' => 'config created successfully', 'data' => $validator->errors()];
        } else {
            return ['status' => 'error', 'message' => 'config creation failed', 'data' => $validator->errors()];
        }
    }

    public function update(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|alpha_dash',
            'value' => 'required|string',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $config = Configuration::where('title', '=', \request('title'))->first();
        if ($config)
        {
            $config->value = \request('value');
            $tmp = $config->save();
            if ($tmp)
            {
                return ['status' => 'success', 'message' => 'config updated successfully', 'data' => null];
            } else {
                return ['status' => 'error', 'message' => 'config update failed', 'data' => null];
            }
        } else {
            return ['status' => 'error', 'message' => 'invalid config', 'data' => null];
        }
    }

    public function destroy(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|alpha_dash'
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $config = Configuration::where('title', '=', \request('title'))->delete();
        if ($config === 1)
        {
            return ['status' => 'success', 'message' => 'config deleted successfully', 'data' => null];
        } else {
            return ['status' => 'error', 'message' => 'config deletion successful', 'data' => null];
        }
    }
}
