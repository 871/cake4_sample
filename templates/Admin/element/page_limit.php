<?php
            
    $defaultLimit = $defaultLimit ?? 20;
    $url = $url ?? [
        '?' => [
            
        ],
    ];

    $url['?']['page'] = 1;
?>
    <span class="page_limit">
        表示
        <select onchange="location.href=this.value">
        <?php 
            $limitList = [10, 20, 50, 100, 200];
        ?>
        <?php if (!in_array((int) $this->getRequest()->getQuery('limit', $defaultLimit), $limitList, true)) { 
                
                $url['?']['limit'] = $this->getRequest()->getQuery('limit', $defaultLimit);
        ?>
            <option 
                value="<?= $this->Url->build($url); ?>"
                selected
            ><?= $this->getRequest()->getQuery('limit', $defaultLimit) ?></option>
        <?php } ?>
        <?php foreach($limitList as $limit) { 

                $url['?']['limit'] = $limit;
        ?>
            <option 
                value="<?= $this->Url->build($url); ?>"
                <?= (int) $this->getRequest()->getQuery('limit', $defaultLimit) === $limit ? 'selected' : '' ?>
            ><?= $limit ?></option>
        <?php } ?>
        </select>
        件
    </span>