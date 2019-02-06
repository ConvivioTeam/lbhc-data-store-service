<?php

namespace App\Component\Model;

use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Application;

class AbstractModel
{

    /**
     * Laravel application container.
     *
     * @var \Laravel\Lumen\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $validFields = [];

    /**
     * @var array
     */
    protected $requiredFields = [];

    protected $missingFields;

    /**
     * @var bool
     */
    protected $flagged = false;

    /**
     * AbstractModel constructor.
     * @param Application $app
     * @throws ModelConfigurationException
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return void
     *
     * @throws ModelConfigurationException
     */
    public function validate()
    {
        if (empty($this->validFields)) {
            throw new ModelConfigurationException(sprintf('Valid fields property not set for %s', get_class($this)));
        }
        if (empty($this->requiredFields)) {
            throw new ModelConfigurationException(sprintf('Required fields property not set for %s', get_class($this)));
        }
    }

    /**
     * @param array $fields
     *
     * @return void
     *
     * @throws ModelConfigurationException
     */
    public function validateFields($fields)
    {
        $this->missingFields = array_diff_key($this->requiredFields, $fields);
        if (!empty($this->missingFields)) {
            throw new ModelConfigurationException(
                sprintf(
                    'Missing required fields: %s',
                    implode('; ', $this->missingFields)
                )
            );
        }
    }

    /**
     * @return array
     */
    public function getValidFields(): array
    {
        return $this->validFields;
    }

    /**
     * @return array
     */
    public function getRequiredFields(): array
    {
        return $this->requiredFields;
    }

    /**
     * @param bool $flagged
     */
    public function setFlagged(bool $flagged): void
    {
        $this->flagged = $flagged;
    }

    /**
     * @return bool
     */
    public function isFlagged(): bool
    {
        return $this->flagged;
    }
}
