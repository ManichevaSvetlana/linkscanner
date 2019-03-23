<template>
    <div class="modal fade" id="new-element-modal" tabindex="-1" role="dialog" aria-labelledby="new-element-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="new-element-modal-label">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <div v-for="field in fields" v-if="field.editable" class="form-group" >
                            <label class="form-check-label" >{{field.title}}</label>
                            <input type="text" class="form-control" v-if="field.field_type == 'text'" v-model="resource[field.dataIndex]">
                            <a-checkbox v-if="field.field_type == 'checkbox'" v-model="resource[field.dataIndex]"></a-checkbox>
                            <textarea class="form-control" rows="3" v-if="field.field_type == 'textarea'" v-model="resource[field.dataIndex]"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="create" data-dismiss="modal">Create</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['fields', 'resource'],
        name: "NewElement",
        methods: {
            create(){
                this.$emit('create');
            }
        }
    }
</script>

<style scoped>

</style>