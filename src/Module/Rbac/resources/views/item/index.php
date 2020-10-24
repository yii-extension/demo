<?php

declare(strict_types=1);

use App\Asset\BuefyAsset;
use App\Asset\VueAxiosAsset;
use App\Module\Rbac\Asset\ItemAsset;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Html\Html;

/**
 * @var AssetManager $assetManager
 */
$assetManager->register([
    BuefyAsset::class,
    VueAxiosAsset::class,
    ItemAsset::class
]);

?>

<div class = 'column'>

    <div id='items' class='container'>

        <b-field grouped position="is-right">

            <span class='perPage mr-4'>
                <b-select v-model='perPage' :disabled='!isPaginated'>
                    <option value='5'>5</option>
                    <option value='10'>10</option>
                    <option value='15'>15</option>
                    <option value='20'>20</option>
                </b-select>
            </span>

            <?= Html::a('Add Item', '/item/create', ['class' => 'button is-link']) ?>

        </b-field>

        <b-table
            :data='items'
            :paginated='isPaginated'
            :per-page='perPage'
            :current-page='currentPage'
            :pagination-position='paginationPosition'
            :default-sort-direction='defaultSortDirection'
            :sort-icon='sortIcon'
            :sort-icon-size='sortIconSize'
            default-sort='items.name'
            aria-next-label='Next page'
            aria-previous-label='Previous page'
            aria-page-label='Page'
            aria-current-label='Current page'
            >

            <b-table-column
                field='id' label='Id' sortable searchable numeric v-slot='props' width='80'
            >
                {{ props.row.id }}
            </b-table-column>

            <b-table-column field='name' cell-class='has-text-left' label='Name' sortable searchable v-slot='props'>
                {{ props.row.name }}
            </b-table-column>

            <b-table-column field='description' cell-class='has-text-left' label='Description' sortable v-slot='props'>
                {{ props.row.description }}
            </b-table-column>

            <b-table-column field='type' centered label='Type' sortable v-slot='props'>
                {{ props.row.type }}
            </b-table-column>

            <b-table-column field='createdAT' centered label='Created' v-slot='props'>
                {{ props.row.created_at }}
            </b-table-column>

            <b-table-column field='updateAT' centered label='Updated' v-slot='props'>
                {{ props.row.updated_at }}
            </b-table-column>

            <b-table-column centered label='Operations' v-slot='props'>
                <a class='fa-stack has-text-success' :href="'/item/edit/' + props.row.id">
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class="fas fa-edit fa-stack-1x fa-inverse"></i>
                </a>

                <a class='fa-stack has-text-danger' href="javascript:void(0)" @click="confirmDelete(props.row.id)">
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class="fas fa-trash fa-stack-1x fa-inverse"></i>
                </a>
            </b-table-column>

        </b-table>

    </div>

</div>
