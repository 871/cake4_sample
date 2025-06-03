<?php

/*
debug([
    $this->request,
    $this->request->getParam('message_id'),
]);
/**/

$messages = (function() : array {
    
    $defaultMsg = '予期せぬエラーが発生しました。[ErrorCode: E5001]';
    $admin_account_id = $this->request->getParam('$admin_account_id');
    $message_id = $this->request->getParam('message_id');
    if ($message_id === null) {
        
        return (array) $defaultMsg;
    }

    $key = ERROR_MESSAGES_KEY . '.' . (string) $admin_account_id . '.' . (string) $message_id;
    
    return (array) $this->request->getSession()->read($key, $defaultMsg);
})();

?>
<?php foreach ($messages as $message) { ?>
<div class="message error">
    <?= h($message) ?>
</div>
<?php } ?>
