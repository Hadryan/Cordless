<?
namespace Element\Button;

class Add extends \Element\Button
{
	public function __construct($action = 'add', $caption = null, $icon = null, $message = null)
	{
		parent::__construct(null, $icon ?: 'add', $caption ?: _('Lägg till'));
		$this->action = $action;
		$this->message = $message;
	}
}