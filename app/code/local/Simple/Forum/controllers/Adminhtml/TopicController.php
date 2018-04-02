<?php

/**
 * webideaonline.com.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://webideaonline.com/licensing/
 *
 */


class Simple_Forum_Adminhtml_TopicController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('forum/topic')
            ->_addBreadcrumb(Mage::helper('forum/topic')->__('Topic Manager'), Mage::helper('forum/topic')->__('Topic Manager'));
        return $this;
    }

 	/**
     * default action
     */
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    /**
     * Create new post
     */
    public function newAction()
    {
    	$this->_forward('edit');
    }

    /**
    * Edit action
    */
    public function editAction()
    {
		$topicId     = $this->getRequest()->getParam('id');
        $topicModel  = Mage::getModel('forum/topic')->load($topicId);
        if ($topicModel->getId() || $topicId == 0)
		{
            Mage::register('topic_data', $topicModel);
            $this->loadLayout();
            $this->_setActiveMenu('forum/topic');
            $this->_addBreadcrumb(Mage::helper('forum/topic')->__('Topic Manager'), Mage::helper('adminhtml')->__('Topic Manager'));
            $this->_addBreadcrumb(Mage::helper('forum/topic')->__('Item Topic'), Mage::helper('adminhtml')->__('Item Topic'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('forum/adminhtml_topic_edit'))
                 ->_addLeft($this->getLayout()->createBlock('forum/adminhtml_topic_edit_tabs'));
            $this->renderLayout();
        }
		else
		{
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('forum/topic')->__('Topic does not exist'));
            $this->_redirect('*/*/');
        }
	}

	/**
    * Save action
    */
	public function saveAction()
    {
        if ( $this->getRequest()->getPost() ) {
            try {
                $postData = $this->getRequest()->getPost();
                $topicModel = Mage::getModel('forum/topic');
                //validate url key
                if($postData['url_text_short'] != '')
                {
                	$notValidUrlKey = Mage::helper('forum/topic')->validateUrlKey('forum/' . $postData['url_text_short'], $this->getRequest()->getParam('id'));
                	if($notValidUrlKey)
	                {
						Mage::getSingleton('adminhtml/session')->addError(Mage::helper('forum/topic')->__('Topic URL Key for specified topic already exist.'));
		                Mage::getSingleton('adminhtml/session')->setTopicData($this->getRequest()->getPost());

						$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
		                return;
					}
				}
				else
				{
					$postData['url_text_short'] = Mage::helper('forum/topic')->buildUrlKeyFromTitle($postData['title'], $this->getRequest()->getParam('id'));
				}
				$description = strip_tags(preg_replace("/[\r\t\n\v]/","",$postData['description']));
					$topicModel->setId($this->getRequest()->getParam('id'))
                    ->setStatus($postData['status'])
                    ->setUrl_text_short($postData['url_text_short'])
                    ->setParent_id($postData['parent_id'])
                    ->setUrl_text('forum/' . $postData['url_text_short'])
                    ->setDescription( $description )
					->setTitle($postData['title']);
                if(!$this->getRequest()->getParam('id'))
                {
					$topicModel->setUser_name('admin');
				}

                if(!$this->getRequest()->getParam('id')) //new item
                {
					$topicModel->setCreated_time(now());
				}
				else
				{
					$topicModel->setUpdate_time(now());
				}


                $topicModel->save();
                $topicModel->setEntityUserId(10000000);
                $id_path     = $this->buildIdPath($topicModel->getId());
                $requestPath = $this->buildRequestPath($topicModel->getId());
                Mage::helper('forum/topic')->updateUrlRewrite($id_path, Mage::app()->getStore()->getId(), 'forum/' . $postData['url_text_short'], $requestPath);
                Mage::helper('forum/topic')->setEntity($topicModel->getId(), $topicModel);
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('forum/topic')->__('Topic was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setTopicData(false);
                $this->_redirect('*/*/');
                return;
            }
			catch (Exception $e)
			{
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setTopicData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    /**
    * Delete action
    */
    public function deleteAction()
    {
		if( $this->getRequest()->getParam('id') > 0 )
		{
            try
			{
				$this->deleteEntity($this->getRequest()->getParam('id'));
				$this->deleteUrlRewrite($this->getRequest()->getParam('id'));
                $this->deleteAllPosts($this->getRequest()->getParam('id'));
				Mage::helper('forum/notify')->deleteByTopicId($this->getRequest()->getParam('id'));
				$topicModel = Mage::getModel('forum/topic');
                $topicModel->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('forum/topic')->__('Topics was successfully deleted!<br>All Topics posts were successfully deleted!'));
                $this->_redirect('*/*/');
            }
			catch (Exception $e)
			{
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
	}

	public function massDeleteAction()
	{
		$params = $this->getRequest()->getParams();
		$ids = $params['topic'];
		if(is_array($ids))
		{
			foreach($ids as $id)
			{
				try
				{
					$this->deleteEntity($id);
					$this->deleteUrlRewrite($id);
	                $this->deleteAllPosts($id);
	                $topicModel = Mage::getModel('forum/topic');
	                $topicModel->setId($id)
	                    ->delete();
	            }
				catch (Exception $e)
				{
	                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
	                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
	                return;
	            }
			}
		}
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('forum/topic')->__('All Topics were successfully deleted!<br>All Topics Posts were successfully deleted!'));
		$this->_redirect('*/*/');
	}

	public function massStatusAction()
	{
		$params = $this->getRequest()->getParams();
		$status = (!empty($params['status']) ? $params['status'] : 0);
		$ids = $params['topic'];
		if(is_array($ids))
		{
			foreach($ids as $id)
			{
				$topic = Mage::getModel('forum/topic')->load($id);
				$topic->setStatus($status==2?0:$status);
				$topic->setUpdate_time(now());
				$topic->save();
			}
		}
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('forum/topic')->__('All Topics statuses were successfully changed.'));
		$this->_redirect('*/*/');
	}

	private function buildRequestPath($id)
	{
		return Mage::helper('forum/topic')->buildRequestPath($id);
	}

	private function buildIdPath($id)
	{
		return Mage::helper('forum/topic')->buildIdPath($id);
	}

	private function deleteUrlRewrite($id)
	{
        $id_path = $this->buildIdPath($id);
		Mage::helper('forum/topic')->deleteUrlRewrite($id_path);
	}

	private function deleteAllPosts($id)
	{
		$collection  = Mage::getModel('forum/post')->getCollection();
		$collection->getSelect()->where('parent_id=?', $id);
		foreach($collection as $post)
		{
			try
			{
				$modelDelete  = Mage::getModel('forum/post');
				$modelDelete->setId($post->getId())
                    ->delete();
			}
			catch(Exception $e)
			{

			}
		}
	}

	private function deleteEntity($id)
	{
		return Mage::helper('forum/topic')->deleteEntity($id);
	}
}

?>