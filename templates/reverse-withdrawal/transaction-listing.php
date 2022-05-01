<?php
/**
 * @var $transactions array
 */
use WeDevs\Dokan\ReverseWithdrawal\Helper as ReverseWithdrawalHelper;
?>
<?php if ( is_wp_error( $transactions ) ) : ?>
    <div class="dokan-alert dokan-alert-danger">
        <strong><?php echo wp_kses_post( $transactions->get_error_message() ); ?></strong>
    </div>
<?php else: ?>
    <table class="dokan-table dokan-table-striped">
        <tr>
            <th><?php esc_html_e( 'Transaction ID', 'dokan-lite' ); ?></th>
            <th><?php esc_html_e( 'Date', 'dokan-lite' ); ?></th>
            <th><?php esc_html_e( 'Transaction Type', 'dokan-lite' ); ?></th>
            <th><?php esc_html_e( 'Note', 'dokan-lite' ); ?></th>
            <th><?php esc_html_e( 'Debit', 'dokan-lite' ); ?></th>
            <th><?php esc_html_e( 'Credit', 'dokan-lite' ); ?></th>
            <th><?php esc_html_e( 'Balance', 'dokan-lite' ); ?></th>
        </tr>
        <?php
        $current_balance = $transactions['balance']['balance'];
        $items[] = $transactions['balance'];
        foreach ( $transactions['items'] as $item ) {
            $items[] = ReverseWithdrawalHelper::get_formated_transaction_data( $item, $current_balance, 'seller' );
        }
        foreach ( $items as $transaction ) {
            ?>
            <tr>
                <td>
                    <?php
                    // translators: 1) transaction url 2) transaction id
                    echo sprintf( '<a href="%1$s" target="_blank">%2$s</a>', $transaction['trn_url'], $transaction['trn_id'] )
                    ?>
                </td>
                <td><?php echo esc_html( $transaction['trn_date'] ); ?></td>
                <td><?php echo esc_html( $transaction['trn_type'] ); ?></td>
                <td><?php echo esc_html( $transaction['note'] ); ?></td>
                <td><?php echo $transaction['debit'] === '' ? '--' : wc_price( $transaction['debit'] ); ?></td>
                <td><?php echo $transaction['credit'] === '' ? '--' : wc_price( $transaction['credit'] ); ?></td>
                <td>
                    <?php echo $transaction['balance'] < 0 ? sprintf( '(%1$s)', wc_price( abs( $transaction['balance'] ) ) ) : wc_price( $transaction['balance'] ); ?>
                </td>
            </tr>
            <?php
        }
        if ( count( $items ) > 1 ) {
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b><?php _e( 'Balance:', 'dokan' ); ?></b></td>
                <td><b><?php echo wc_price( $current_balance ); ?></b></td>
            </tr>
            <?php
        } else {
            ?>
            <tr>
                <td colspan="7">
                    <?php esc_html_e( 'No transactions found!', 'dokan-lite' ); ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
<?php endif; ?>
