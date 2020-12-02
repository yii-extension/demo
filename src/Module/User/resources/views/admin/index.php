<?php

declare(strict_types=1);

use App\Asset\BuefyAsset;
use App\Asset\VueAxiosAsset;
use App\Module\User\Asset\AdminAsset;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Html\Html;

/**
 * @var string $action
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

            <b-table-column cell-class='is-vmiddle' field='avatar' label='avatar' v-slot='props' width='30'>
                <div>
                    <img :src="'/images/avatars/' + props.row.id + '.svg'"></img>
                </div>
            </b-table-column>

            <b-table-column
                cell-class='is-vmiddle'
                field='id'
                label='Id'
                sortable
                searchable
                numeric
                v-slot='props'
                width='80'
            >
                {{ props.row.id }}
            </b-table-column>

            <b-table-column
                field='username'
                cell-class='has-text-left is-vmiddle'
                label='UserName'
                sortable
                searchable
                v-slot='props'
                width='120'
            >
                {{ props.row.username }}
            </b-table-column>

            <b-table-column
                field='email' cell-class='has-text-left is-vmiddle' label='Email' sortable searchable v-slot='props'
            >
                {{ props.row.email }}
            </b-table-column>

            <b-table-column cell-class='is-vmiddle' field='ip' centered label='Ip' v-slot='props' width='100'>
                {{ props.row.registration_ip }}
            </b-table-column>

            <b-table-column cell-class='is-vmiddle' field='createdAT' centered label='Created' v-slot='props'>
                {{ props.row.created_at }}
            </b-table-column>

            <b-table-column cell-class='is-vmiddle' field='lastLogin' centered label='Last Login' v-slot='props'>
                {{ props.row.last_login_at }}
            </b-table-column>

            <b-table-column cell-class='is-vmiddle' field='blocked' centered label='Block' v-slot='props' width='20'>
                <a
                    class='fa-stack has-text-success'
                    slot="blocked"
                    v-if="props.row.blocked > '0'"
                    @click='unblockUser(props.row.id)'
                >
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class='fas fa-user-alt fa-stack-1x fa-inverse'></i>
                </a>
                <a class='fa-stack has-text-danger' v-else href='javascript:void(0)' @click='blockUser(props.row.id)'>
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class='fas fa-user-lock fa-stack-1x fa-inverse'></i>
                </a>
            </b-table-column>

            <b-table-column cell-class='is-vmiddle' field='confirm' centered label='Confirm' v-slot='props'>
                <span class='has-text-success' slot="confirm" v-if="props.row.confirm > '0'">
                    <b>Confirm</b>
                </span>
                <a class='fa-stack has-text-info' v-else href='javascript:void(0)' @click='confirmUser(props.row.id)'>
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class='fas fa-user-times fa-stack-1x fa-inverse'></i>
                </a>
            </b-table-column>

            <b-table-column cell-class='is-vmiddle' field='operations' centered label='Operations' v-slot='props'>
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
                <a class='fa-stack has-text-dark' href='javascript:void(0'  @click='resendPassword(props.row.id)'>
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class="fas fa-envelope fa-stack-1x fa-inverse"></i>
                </a>
            </b-table-column>

        </b-table>

    </div>

</div>
