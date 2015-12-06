<?php namespace Scheduler\Shifts\Requests;

use Scheduler\Http\Requests\Request;

/**
 * Class UpdateShiftRequest
 * @package Scheduler\Shifts\Requests
 * @author Sam Tape <sctape@gmail.com>
 */
class UpdateShiftRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'break' => 'required|numeric',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ];
    }
}
