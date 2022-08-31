<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * TblUsers Controller
 *
 * @property \App\Model\Table\TblUsersTable $TblUsers
 * @method \App\Model\Entity\TblUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TblUsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $tblUsers = $this->paginate($this->TblUsers);

        $this->set(compact('tblUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id Tbl User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $tblUser = $this->TblUsers->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('tblUser'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $tblUser = $this->TblUsers->newEmptyEntity();
        if ($this->request->is('post')) {
            $tblUser = $this->TblUsers->patchEntity($tblUser, $this->request->getData());
            if ($this->TblUsers->save($tblUser)) {
                $this->Flash->success(__('The tbl user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The tbl user could not be saved. Please, try again.'));
        }
        $this->set(compact('tblUser'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Tbl User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $tblUser = $this->TblUsers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $tblUser = $this->TblUsers->patchEntity($tblUser, $this->request->getData());
            if ($this->TblUsers->save($tblUser)) {
                $this->Flash->success(__('The tbl user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The tbl user could not be saved. Please, try again.'));
        }
        $this->set(compact('tblUser'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Tbl User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $tblUser = $this->TblUsers->get($id);
        if ($this->TblUsers->delete($tblUser)) {
            $this->Flash->success(__('The tbl user has been deleted.'));
        } else {
            $this->Flash->error(__('The tbl user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
