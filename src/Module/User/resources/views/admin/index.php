<?php

declare(strict_types=1);

use App\Asset\BuefyAsset;
use App\Asset\VueAxiosAsset;
use App\Module\User\Asset\AdminAsset;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Html\Html;

/**
 * @var AssetManager $assetManager
 */
$assetManager->register([
    BuefyAsset::class,
    VueAxiosAsset::class,
    AdminAsset::class
]);

?>

<div class = 'column'>

    <div id='users' class='container'>

        <b-field grouped position="is-right">

            <span class='perPage mr-4'>
                <b-select v-model='perPage' :disabled='!isPaginated'>
                    <option value='5'>5</option>
                        <option value='10'>10</option>
                        <option value='15'>15</option>
                        <option value='20'>20</option>
                </b-select>
            </span>

            <?= Html::a('Add User', $action, ['class' => 'button is-link']) ?>

        </b-field>

        <b-table
            :data='users'
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
                field='id' label='Id' width='80' sortable searchable numeric v-slot='props'
            >
                {{ props.row.id }}
            </b-table-column>

            <b-table-column
                field='username'
                cell-class='has-text-left'
                label='UserName'
                width='140'
                sortable
                searchable
                v-slot='props'
            >
                {{ props.row.username }}
            </b-table-column>

            <b-table-column
                field='email' cell-class='has-text-left' label='Email' sortable searchable v-slot='props'
            >
                {{ props.row.email }}
            </b-table-column>

            <b-table-column field='ip' centered label='Ip' width='100' sortable searchable v-slot='props'>
                {{ props.row.registration_ip }}
            </b-table-column>

            <b-table-column field='createdAT' centered label='Created' sortable v-slot='props'>
                {{ props.row.created_at }}
            </b-table-column>

            <b-table-column field='lastLogin' centered label='Last Login' sortable v-slot='props'>
                {{ props.row.last_login_at }}
            </b-table-column>

            <b-table-column field='confirm' centered label='Confirm' sortable v-slot='props'>
                <span slot="confirm" v-if="props.row.confirm > '0'">
                    <p class='has-text-success'><b>Confirm</b></p>
                </span>
                <a slot="confirm" v-else>
                    <i class='fas fa-user-times'></i>
                </a>
            </b-table-column>

            <b-table-column field='operations' centered label='Operations' v-slot='props'>
                <a class='fa-stack has-text-info' :href="'/admin/info/' + props.row.id">
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class="fas fa-eye fa-stack-1x fa-inverse"></i>
                </a>
                <a class='fa-stack has-text-success' :href="'/admin/edit/' + props.row.id">
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class="fas fa-edit fa-stack-1x fa-inverse"></i>
                </a>
                <a class='fa-stack has-text-danger' href='javascript:void(0)' @click='confirmDelete(props.row.id)'>
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class="fas fa-trash fa-stack-1x fa-inverse"></i>
                </a>
                </a>
                <a class='fa-stack has-text-dark' href='javascript:void(0'  @click='resendPassword(props.row.id)'>
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class="fas fa-envelope fa-stack-1x fa-inverse"></i>
                </a>
            </b-table-column>

        </b-table>

    </div>

</div>
