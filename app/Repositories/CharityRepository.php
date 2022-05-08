<?php
/**
 * Charity Repository
 */
namespace App\Repositories;

use App\Models\Charity;
use App\Repositories\BaseRepository;

/**
 * Class CharityRepository
 * @package App\Repositories
 * @version May 7, 2022, 9:19 pm UTC
*/

class CharityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return Charity::class;
    }
}
