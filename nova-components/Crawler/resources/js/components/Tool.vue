<template>
    <div>
        <heading class="mb-6">Crawler</heading>
        <a class="btn btn-default btn-primary" style="float:right" href="/check">Run Checkout</a>
        <br>
        <br>
        <br>
        <div class="card mb-6 py-3 px-6">
            <div class="flex border-b border-40">
                <div class="w-1/4 py-4">
                    <h4 class="font-normal text-80">Checkout Time</h4>
                </div>
                <div class="w-3/4 py-4">
                    <select data-testid="users-select" dusk="user" class="form-control form-select mb-3 w-full" @change="setTime" v-model="time">
                        <option value="00:00" selected="selected">12 am</option>
                        <option value="01:00" >1 am</option>
                        <option value="02:00" >2 am</option>
                        <option value="03:00" >3 am</option>
                        <option value="04:00" >4 am</option>
                        <option value="05:00" >5 am</option>
                        <option value="06:00" >6 am</option>
                        <option value="07:00" >7 am</option>
                        <option value="08:00" >8 am</option>
                        <option value="09:00" >9 am</option>
                        <option value="10:00" >10 am</option>
                        <option value="11:00" >11 am</option>
                        <option value="12:00" >12 pm</option>
                        <option value="13:00" >1 pm</option>
                        <option value="14:00" >2 pm</option>
                        <option value="15:00" >3 pm</option>
                        <option value="16:00" >4 pm</option>
                        <option value="17:00" >5 pm</option>
                        <option value="18:00" >6 pm</option>
                        <option value="19:00" >7 pm</option>
                        <option value="20:00" >8 pm</option>
                        <option value="21:00" >9 pm</option>
                        <option value="22:00" >10 pm</option>
                        <option value="23:00" >11 pm</option>
                    </select>
                </div>
            </div>
            
        </div>
        <notifications group="notify" position="bottom right"/>
    </div>
</template>

<script>
export default {
    mounted() {
        //
    },
    data()
    {
        return{
            time: "00:00"
        }
    },
    created()
    {
        this.getTime();
    },
    methods:{
        getTime()
        {
            axios.get('/checkout-time').then(response => {
                this.time = response.data;
                console.log(this.time);
            }).catch(e => {
                this.$notify({
                    group: 'notify',
                    title: 'Error while loading checkout time',
                    text: e.message,
                    type: 'warn'
                });
            });
        },
        setTime()
        {
            axios.post('/checkout-time', {checkout_time: this.time}).then(response => {
                this.$notify({
                    group: 'notify',
                    title: 'Success',
                    text: response.data.message,
                    type: 'success'
                });
            }).catch(e => {
                this.$notify({
                    group: 'notify',
                    title: 'Error while setting checkout time',
                    text: e.message,
                    type: 'warn'
                });
            });
        }
    }
}
</script>

<style>
/* Scoped Styles */
</style>
