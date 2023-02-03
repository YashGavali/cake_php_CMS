<h1>Articles</h1>
<table>
    <tr>
        <th>Title</th>
        <th>Created</th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($articles as $article) : ?>
        <tr>
            <td>
                <?= $this->Html->link($article->title, ['action' => 'view', $article->slug]) ?>
            </td>
            <td>
                <?= $article->created->format(DATE_RFC850) ?>
            </td>
            <td>
                <?= $this->Html->link('Edit', ['action' => 'edit', $article->slug]) ?>
            </td>
            <td>
                <?= $this->Form->postLink(
                    'Delete',
                    ['action' => 'delete', $article->slug],
                    ['confirm' => 'Are you sure?']
                ) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?= $this->Html->link('Add Article', ['action' => 'add']) ?>
    <div>
        <?php echo $cookieValue  ?>

    </div>
</table>