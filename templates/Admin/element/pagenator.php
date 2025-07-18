<?php
            
    $pagenator = [
        'url' => $url,
    ];
?>
    <ul class="pagination">
        <?php
            unset($pagenator['url']['?']['page']);
        ?>
        <?= join('', [
            $this->Paginator->first('<< ' . __('first'), $pagenator),
            $this->Paginator->prev('< ' . __('prev'), $pagenator),
            $this->Paginator->numbers($pagenator),
            $this->Paginator->next(__('next') . ' >', $pagenator),
            $this->Paginator->last(__('last') . ' >>', $pagenator),
        ]) ?>
    </ul>