<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * CustomerRegistration Controller
 *
 * @method \App\Model\Entity\CustomerRegistration[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CustomerRegistrationController extends AppController
{
    public function index()
    {
        // 入力初期値設定
        $this->request->getSession()->write(self::class, [
            'username' => 'Inti User Name',
            'password' => 'Init password',
        ]);
        
        $this->redirect(['action' => 'step01']);
    }

    public function step01()
    {
        $this->set('data', $this->request->getSession()->read(self::class));
        
        return $this->render('/CustomerRegistration/step01');
    }
    
    public function step01Post()
    {
        if ($this->request->is('get')) {
            return $this->redirect(['action' => 'step01']);
        }
        
        $data = $this->request->getData();
        $form = new \App\Form\CustomerRegistrationForm();
        if ($form->execute($this->request->getData())) {
            
            $oldData = $this->request->getSession()->read(self::class);
            $this->request->getSession()->write(self::class, array_merge($oldData, $data));
            
            return $this->redirect(['action' => 'step02']);
        }
        
        $this->Flash->error(__('入力エラーがあります。'));
        $this->set('data', $this->request->getData());
        $this->set('errors', $form->getErrors());
        
        return $this->render('/CustomerRegistration/step01');
    }
    
    public function step02()
    {
        $this->set('data', $this->request->getSession()->read(self::class));
        
        return $this->render('/CustomerRegistration/step02');
    }
    
    public function step02Post()
    {
        if ($this->request->is('get')) {
            return $this->redirect(['action' => 'step02']);
        }
        
        $data = $this->request->getData();
        $form = new \App\Form\CustomerRegistrationForm();
        if ($form->execute($this->request->getData())) {
            
            $oldData = $this->request->getSession()->read(self::class);
            $this->request->getSession()->write(self::class, array_merge($oldData, $data));
            
            return $this->redirect(['action' => 'conf']);
        }
        
        $this->Flash->error(__('入力エラーがあります。'));
        $this->set('data', $data);
        $this->set('errors', $form->getErrors());
        
        return $this->render('/CustomerRegistration/step02');
    }
    
    public function conf()
    {
        $this->set('data', $this->request->getSession()->read(self::class));
        
        return $this->render('/CustomerRegistration/conf');
    }
    
    public function confPost()
    {
        if ($this->request->is('get')) {
            return $this->redirect(['action' => 'conf']);
        }
        
        $data = $this->request->getSession()->read(self::class);
        $form = new \App\Form\CustomerRegistrationForm();
        if ($form->execute($data)) {
            
            return $this->redirect(['action' => 'comp']);
        }
        
        $this->Flash->error(__('入力エラーがあります。'));
        
        return $this->redirect(['action' => 'step01']);
    }
    
    public function comp()
    {
        $this->request->getSession()->delete(self::class);
        
        return $this->render('/CustomerRegistration/comp');
    }
    
    
}
