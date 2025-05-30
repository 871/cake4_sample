<?php

/*
debug([
    $this->request,
    $this->request->getParam('message_id'),
]);
/**/

$message_id = $this->request->getParam('message_id');

$message = $this->request->getSession()->read(ERROR_MESSAGES_KEY . '.other.' . $message_id);


$messages = (function() : array {
    
    $defaultMsg = '予期せぬエラーが発生しました。[ErrorCode: E5001]';
    $message_id = $this->request->getParam('message_id');
    if ($message_id === null) {
        
        return (array) $defaultMsg;
    }

    return (array) $this->request->getSession()->read(ERROR_MESSAGES_KEY . '.other.' . (string) $message_id, $defaultMsg);
})();


?>
<?php foreach ($messages as $message) { ?>
<div class="error">
    <?= h($message) ?>
</div>
<?php } ?>
