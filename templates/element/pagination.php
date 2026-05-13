<?php
/**
 * Pagination Element
 *
 * @var \App\View\AppView $this
 */
if (!$this->Paginator->hasPage()) {
    return;
}
?>
<div class="pagination">
    <?= $this->Paginator->first('&laquo; ' . __('First'), ['escape' => false, 'class' => 'pagination__link']) ?>
    <?= $this->Paginator->prev('&lsaquo; ' . __('Prev'), ['escape' => false, 'class' => 'pagination__link']) ?>
    <?= $this->Paginator->numbers(['class' => 'pagination__link', 'currentClass' => 'pagination__link--active']) ?>
    <?= $this->Paginator->next(__('Next') . ' &rsaquo;', ['escape' => false, 'class' => 'pagination__link']) ?>
    <?= $this->Paginator->last(__('Last') . ' &raquo;', ['escape' => false, 'class' => 'pagination__link']) ?>
</div>
<div class="pagination__info">
    <?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?>
</div>
