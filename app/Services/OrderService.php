<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    /**
     * The payment gateway for processing payments.
     *
     * @var
     */
    private $checkoutPaymentGateway;

    /**
     * The inventory manager for updating inventory.
     *
     * @var
     */
    private $inventoryManager;

    /**
     * The OrderService constructor.
     *
     * @param $checkoutPaymentGateway
     * @param $inventoryManager
     */
    public function __construct($checkoutPaymentGateway, $inventoryManager)
    {
        $this->checkoutPaymentGateway = $checkoutPaymentGateway;
        $this->inventoryManager = $inventoryManager;
    }

    /**
     * Validates the order, calculates order details, processes the order and payment,
     * notifies the customer, and sends an invoice.
     *
     * @param Order $order
     *
     * @return void
     */
    public function placeOrder(Order $order): void
    {
        $order = $this->validateOrder($order);
        $order = $this->calculateOrderDetails($order);

        $this->processOrder($order);
        $this->processPayment($order);

        $this->notifyCustomer($order);
        $this->sendInvoice($order);
    }

    /**
     * Stores the order and updates the inventory.
     *
     * @param $order
     *
     * @return void
     */
    private function processOrder($order): void
    {
        $this->storeOrder($order);

        $this->inventoryManager->updateInventory($order);
    }

    /**
     * Uses the checkout payment gateway to process the payment.
     *
     * @param $order
     *
     * @return void
     */
    private function processPayment($order): void
    {
        $this->checkoutPaymentGateway->processPayment($order->getTotalAmount());
    }

    /**
     * Sends a notification to the customer about the order.Options can include
     * Push or SMS notifications.
     *
     * @param $order
     *
     * @return void
     */
    private function notifyCustomer($order)
    {
        // Notify the customer about the order.
    }

    /**
     * Sends an invoice to the customer. Options can include email with invoice attachment
     * or SMS with an invoice link.
     *
     * @param $order
     *
     * @return void
     */
    private function sendInvoice($order)
    {
        // Send the invoice to the customer.
    }

    /**
     * Validates the order data.
     *
     * @param $order
     *
     * @return bool
     * @thows ValidationException
     */
    private function validateOrder($order): bool
    {
        // Validate the order data.

        return true;
    }

    /**
     * Calculates the details of the order such as total amount, taxes, discounts, etc.
     *
     * @param $order
     *
     * @return void
     */
    private function calculateOrderDetails($order)
    {
        // Calculate the order details.
    }

    /**
     * Stores the order in the database or any other storage mechanism.
     *
     * @param $order
     *
     * @return void
     */
    private function storeOrder($order)
    {
        // Store the order.
    }


}
