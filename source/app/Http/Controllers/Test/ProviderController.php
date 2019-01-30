<?php

namespace App\Http\Controllers\Test;

use App\Component\Utility\Database\DbInsert;
use App\Component\Utility\Database\DbSelect;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProviderController extends Controller
{
    /**
     * @var string
     */
    private $table = 'providers';

    /**
     * @var string
     */
    private $dateFormat = 'Y-m-d H:i:s';

    /**
     * @var \DateTime
     */
    private $dateTime;

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

    private $validFields = [
        'id' => 'id',
        'name' => 'name',
        'published' => 'published',
        'venue_id' => 'venue_id',
        'contact_id' => 'contact_id',
        'created' => 'created',
        'updated' => 'updated',
        'flagged' => 'flagged',
    ];

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
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $id = uniqid('', true);
        $defaultParams = [
            'id' => $id,
            'created' => $this->dateTime()->format($this->dateFormat),
            'updated' => $this->dateTime()->format($this->dateFormat),
        ];
        // Merge in defaults so important defaults accidentally set by user is overwritten.
        $params = [
            'values' => array_merge($request->all(), $defaultParams)
        ];
        $this->dbInsert = new DbInsert($this->table);
        $this->dbInsert->dispatch('create', $params);
        return $this->get($id);
    }

    public function update(Request $request, $id)
    {
        $defaultValues = [
            'updated' => $this->dateTime()->format($this->dateFormat),
        ];
        $values = array_merge($defaultValues, $request->all());
        $validFields = $this->validFields;
        unset($validFields['created']);
        unset($validFields['id']);
        $values = array_intersect_key($values, $validFields);
        $params = [
            'id' => $id,
            'values' => $values,
        ];
        $this->dbInsert = new DbInsert($this->table);
        $this->dbInsert->dispatch('update', $params);
        return $this->get($id);
    }

    /**
     * @return \DateTime
     *
     * @throws \Exception
     */
    private function dateTime()
    {
        if (!isset($this->dateTime)) {
            $tz = new \DateTimeZone('Europe/London');
            $this->dateTime = new \DateTime('now', $tz);
        }
        return $this->dateTime;
    }
}
