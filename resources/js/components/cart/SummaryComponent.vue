<template>
    <div>
        <div id="summary">
                <h4>Shopping Cart Summary</h4>
                <div class="row" v-if="!productCartItem.length">
                    <div class="col-md-12">
                       Your cart is empty
                    </div>
                </div>
                <template v-if="productCartItem.length">
                    <div class="row mb-3" v-for="product in productCartItem">
                        <div class="col-md-8">
                            <img :src="'http://checkout.test/products/'+product.image"   width="50" height="50" class="mr-3">
                            <span>{{ product.name }}</span>
                        </div>
                        <div class="col-md-4 text-right">{{ product.price }} PHP</div>
                    </div>
                    <div class="row total"  >
                        <div class="col-md-6 text-right">
                            <span>Total</span>
                        </div>
                        <div class="col-md-6 text-right " ><strong>{{ total }} PHP</strong></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="javascript:void(0);" class="btn btn-secondary btn-block" v-on:click="$emit('continueCheckout', true)">Continue Checkout</a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
</template>

<script>
    export default {
        name: 'checkout_summary',
        props: ["productCartItem","continueCheckout"],
        data () {
            return {
                isCheckout: false,
            }
        },
        computed: {
           total: function () {
                var self = this;
                var sum = 0;
                self.productCartItem.forEach(e => {
                    sum += Number(e.price);
                });
                return sum.toFixed(2);
            }
        },
        mounted() {
            console.log('Component mounted.');
        },
        created() {
            console.log('Component created');
        },
        method: {
            // 
        }
    }
</script>
