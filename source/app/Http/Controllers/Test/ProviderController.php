<?php

namespace App\Http\Controllers\Test;

use App\Component\Utility\Database\DbInsert;
use App\Component\Utility\Database\DbSelect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProviderController extends Controller
{
    /**
     * @var string
     */
    private $table = 'providers';

    private $dateFormat = 'Y-m-d H:i:s';

    /**
     * @var \App\Component\Utility\Database\DbSelect
     */
    protected $dbSelect;

    /**
     * @var \App\Component\Utility\Database\DbInsert
     */
    protected $dbInsert;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $response;

    public function __construct()
    {
        $this->dbSelect = new DbSelect($this->table);
    }

    public function index()
    {
        $this->response = $this->dbSelect->dispatch('index');
        return response()->json($this->response);
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $this->response = $this->dbSelect->dispatch('getById', $id);
        return response()->json($this->response);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
//        Log::debug(print_r($request->all(), true));
        $tz = new \DateTimeZone('Europe/London');
        $datetime = new \DateTime('now', $tz);
        $id = uniqid('', true);
        $defaultParams = [
            'id' => $id,
            'created' => $datetime->format($this->dateFormat),
            'updated' => $datetime->format($this->dateFormat),
        ];
        $params = array_merge($defaultParams, $request->all());
        $this->dbInsert = new DbInsert($this->table);
        $newId = $this->dbInsert->dispatch('create', $params);
        Log::debug(print_r(['id' => $id, 'newId' => $newId], true));
        return $this->get($id);
    }

    public function update($params)
    {

    }
}
