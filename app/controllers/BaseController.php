<?php

class BaseController extends Controller
{

    /**
     * @var array $viewData
     */
    protected $viewData;
    /**
     * @var User
     */
    protected $currentUser;

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    public function __construct()
    {
        $this->currentUser = Auth::user();
        $this->viewData = [
            'currentUser' => $this->currentUser,
        ];
    }
}
