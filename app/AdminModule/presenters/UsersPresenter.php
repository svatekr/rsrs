<?php
  
namespace App\AdminModule\Presenters;
  
use App\Model\UsersRepository;
use App\Model\UsersEntity;
use Grido\Grid;
use Nette\Security\Passwords;
use Nette\Forms\Form;
use App\Forms\EditUserFormFactory;
use App\Forms\AddUserFormFactory;
 
/**
 * Class UsersPresenter
 * @package App\AdminModule\Presenters
 */
class UsersPresenter extends BasePresenter 
{
 
	/** @var UsersRepository @inject */
	public $usersRepository;
 
	/** @var EditUserFormFactory @inject */
	public $editUserFormFactory;

	/** @var AddUserFormFactory @inject */
	public $addUserFormFactory;

	/** @var UsersEntity */
	public $userEntity;

	public function startup() {
		parent::startup();
	}
  
	/**
	 * @param int $id
	 */
	public function renderEdit($id = 0) {
		/** @var Form $form */
		$form = $this['editForm'];
		if (!$form->isSubmitted()) {
			$item = $this->usersRepository->get($id);
			$row = $this->usersRepository->itemToArray($item);
			if (!$row)
				throw new PDOException('Záznam nenalezen');
			$form->setDefaults($row);
		}
	}

	/**
	 * @return Form
	 */
	protected function createComponentEditForm() {
		$form = $this->editUserFormFactory->create();
		$form->setTranslator($this->translator);
	 
		$form->onSuccess[] = function (Form $form) {
			$values = $form->getValues();
			$this->userEntity = $this->usersRepository->get($values->id);
			$error = 0;
	 
			if (!$this->isNonDuplicite('email', $values)) {
				$this->flashMessage('Tento mail používá jiný uživatel.', 'danger');
				$error = 1;
			}
	 
			if (trim($values->password) != '')
				$this->userEntity->password(Passwords::hash($values->password));
	 
			if ($error == 0) {
				$this->userEntity->name($values->name);
				$this->userEntity->lastname($values->lastname);
				$this->userEntity->email($values->email);
				$this->userEntity->role($values->role);
				$this->usersRepository->save($this->userEntity);
				$this->flashMessage('Změny byly uloženy', 'success');
				$this->redirect('default');
			}
		};
		return $form;
	}

	/**
	 * @return Form
	 */
	protected function createComponentAddForm() {
		$form = $this->addUserFormFactory->create();
		$form->setTranslator($this->translator);
		
		$form->onSuccess[] = function (Form $form) {
			$values = $form->getValues();
			$error = FALSE;

			if ($this->usersRepository->getOneWhere(['email' => $values->email])) {
				$this->flashMessage($this->translator->translate('messages.users.mail_use'), 'danger');
				$error = TRUE;
			}
			
			if ($this->usersRepository->getOneWhere(['username' => $values->username])) {
				$this->flashMessage('Uživatel s tímto username již existuje.', 'danger');
				$error = TRUE;
			}

			if (!$error) {
				$this->userEntity = new UsersEntity();
				$this->userEntity->username($values->username);
				$this->userEntity->password(Passwords::hash($values->password));
				$this->userEntity->name($values->name);
				$this->userEntity->lastname($values->lastname);
				$this->userEntity->email($values->email);
				$this->userEntity->role($values->role);
				$this->usersRepository->save($this->userEntity);
				$this->flashMessage('Záznam byl vytvořen.', 'success');
				$this->redirect('default');
			}
		};
		return $form;
	}
	
	/**
	 * @param $id
	 */
	public function handleDelete($id) {
		$this->usersRepository->delete($id);
		$this->flashMessage('Uživatel byl smazán.', 'success');
		$this->redirect('default');
	}

	/**
	 * @param $name
	 * @return Grid
	 */
	protected function createComponentGrid($name) {
		$grid = new Grid($this, $name);
		$grid->translator->lang = 'cs';
  
		$fluent = $this->usersRepository->getAll();
		$grid->model = $fluent;
  
		$grid->addColumnText('username', 'Username')
				->setSortable()
				->setFilterText();
  
		$grid->addColumnText('name', 'Name')
				->setSortable()
				->setFilterText();
  
		$grid->addColumnText('lastname', 'Lastname')
				->setSortable()
				->setFilterText();
  
		$grid->addColumnText('role', 'Role')
				->setSortable()
				->setFilterText();

		$grid->addActionHref('edit', '')
				->setIcon('pencil');
  
		$grid->addActionEvent('delete', '')
				->setCustomRender(function ($item) {
					$i = \Nette\Utils\Html::el('i', ['class' => 'fa fa-trash']);
					$el = \Nette\Utils\Html::el('a', ['class' => 'btn btn-default btn-xs btn-mini ajax'])
							->href($this->presenter->link("delete!", $item->id))
							->setHtml($i);
					return $el;
				});

		$grid->setDefaultPerPage(50);
		$grid->filterRenderType = $this->filterRenderType;
		$grid->setExport();
  
		return $grid;
	}
	 
	/**
	 * @param $field
	 * @param $values
	 * @return bool
	 */
	private function isNonDuplicite($field, $values) {
		if ($values->$field == $this->userEntity->$field())
			return (count($this->usersRepository->getAllWhere([$field => $values->$field])) == 1);
		else
			return (count($this->usersRepository->getAllWhere([$field => $values->$field])) == 0);
	}

}
