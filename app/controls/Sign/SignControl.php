<?php

use Nette\Application\UI,
	Nette\Security as NS;

class SignControl extends \Nette\Application\UI\Control
{
	public $redirect;
	public $backlink;
	protected $db;

	/**
	 * Expiration value if user ticks "Remember me"
	 * @var type
	 */
	public $rememberExpiration = '+ 5 days';

	/**
	 * Expiration value if user does NOT tick "Remember me"
	 * @var type
	 */
	public $normalExpiration = '+ 20 minutes';

	public function render() {
		$this->template->setFile(dirname(__FILE__) . '/template.latte');
		$this->template->userIdentity = $this->presenter->user->getIdentity();
		$this->template->render();
	}

	/**
	 * Sign in form component factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = new UI\Form;
		$form->addText('username', 'Uživatel:')
			->setRequired('Zadejte uživatelské jméno.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Zadejte heslo.');

		$form->addCheckbox('remember', 'Zapamatovat');

		$form->addSubmit('send', 'Přihlásit');

		$form->onSuccess[] = callback($this, 'signInFormSubmitted');
		return $form;
	}



	public function signInFormSubmitted($form)
	{
		try {
			$values = $form->getValues();
			if ($values->remember) {
				$this->presenter->user->setExpiration(
					$this->rememberExpiration, FALSE
				);
			} else {
				$this->presenter->user->setExpiration(
					$this->normalExpiration, TRUE
				);
			}
			$this->presenter->user->login(
				$values->username, $values->password
			);
			$this->presenter->redirect('Homepage:default');

		} catch (NS\AuthenticationException $e) {
			$this->presenter->flashMessage($e->getMessage(),'error');
		}
	}



	public function handleLogOut()	{
		$this->presenter->getUser()->logout(TRUE);
		$this->presenter->flashMessage('You have been signed out.');
		$this->presenter->redirect('this');
	}

}
