<template>
    <div class="editable-cell">
        <div :style="!editable ? 'display:none' : ''"
                class="editable-cell-input-wrapper"
        >
            <input type="text" class="ant-input" :value="value"
                   @change="handleChange"
                   @pressEnter="check" v-if="!type || type != 'checkbox'"
                   @blur="check" :id="element_id">

            <a-checkbox :value="value" :defaultValue="value" :defaultChecked="value"
                        @change="handleChangeCheckbox" v-else @blur="check" :id="element_id">
            </a-checkbox>
        </div>
        <div
                v-if="!editable"
                class="editable-cell-text-wrapper"
                v-on:click="edit"
        >
            <label v-if="type != 'checkbox'">{{ value || ' ' }}</label>
            <span class="inline-block rounded-full w-2 h-2 " :class="value ? 'bg-success' : 'bg-danger'" v-else></span>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['text', 'type', 'index', 'record_key', 'element_id'],
        data () {
            return {
                value: this.text,
                editable: false,
            };
        },
        methods: {
            created()
            {
                if(this.type == 'checkbox') this.text = Boolean( this.text );
                console.log(this.text);
            },
            handleChange (e) {
                const value = e.target.value;
                this.value = value;
            },
            handleChangeCheckbox (e) {
                const value = e.target.checked;
                this.value = value;
            },
            check () {
                console.log('changed from cell');
                this.editable = false;
                this.$emit('change', {value: this.value, index: this.index, key: this.record_key});
            },
            edit (e) {
                this.editable = true;
                var element_id = this.element_id;
                $('#' + element_id).focus();
                e.preventDefault();
                console.log($('#' + this.element_id).is(":focus"));
            }
        },
    };
</script>
<style>
    .w-2 {
        width: .5rem;
    }
    .h-2 {
        height: .5rem;
    }
    .bg-danger {
        background-color: var(--danger);
    }
    .inline-block {
        display: inline-block;
    }
    .rounded-full {
        border-radius: 9999px;
    }
    .bg-success {
        background-color: var(--success);
    }
</style>