<template>
    <div>

        <button class="btn btn-success" data-toggle="modal" data-target="#new-element-modal" style="float: right;"
                v-if="!isLoading">Add a new Link
        </button>
        <button class="btn btn-success" style="float: right;" v-else>Adding...</button>
        <br>
        <br>
        <br>
        <links-add-form :fields="columns" :resource="newLink" @create="create"></links-add-form>
        <a-table :columns="columns" :dataSource="dataSource" :scroll="{ x: 1500}" :pagination="false">
            <template v-for="column in columns" :slot="column.dataIndex" slot-scope="text, record">
                <editable-cell v-if="column.editable" :text="column.field_type == 'checkbox' ? Boolean( text ) : text " @change="onCellChange" :record_key="record.key" :index="column.dataIndex" :type="column.field_type" :element_id="'editable-cell-' + record.key"></editable-cell>

                <label v-else-if="!column.tag || !text">{{text ? text : ' - '}}</label>
                <a-tag v-else :color="text == 200 ? 'green' : 'red'" :key="column.id">{{text}}</a-tag>
            </template>



            <template slot="operation" slot-scope="text, record">
                <a-popconfirm
                        title="Sure to delete?"
                        @confirm="() => onDelete(record, record.id)">
                    <a href="javascript:;" v-if="!record.isLoading">Delete</a>
                    <a href="javascript:;" v-else>Loading...</a>
                </a-popconfirm>
            </template>
        </a-table>
        <br>
        <br>
        <a-pagination :total="elements.total" :pageSize="5" @change="changePage" style="float:right"/>
        <br>
        <br>
        <br>
    </div>
</template>

<script>
    import EditableCell from '../../parts/EditableCell';

    export default {
        props: ['columns', 'data', 'resource', 'user_timezone'],
        name: "Table",
        components: {
            EditableCell,
        },
        data() {
            return {
                isLoading: false,
                elements: this.data,
                dataSource: [],
                count: 0,
                newLink: {
                    checked_url: '', presence_url: '', email: '', details: '', active: 1, checked_url_status_code: '',
                    presence_url_status_code: '',
                    presence_url_added: '',
                    presence_url_removed: '',
                    last_check: '',
                },

            }
        },
        created() {
            this.dataSource = this.elements['data'];
            this.count = this.dataSource.total;

            console.log(this.dataSource);
        },
        methods: {
            onCellChange(record) {
                this.updateValue(record.value, record.index, record.key);
            },
            updateValue(value, dataIndex, key)
            {
                let json = '{"' + dataIndex + '" : "' + value + '" }';
                axios.put('/' + this.resource + '/' + key, JSON.parse(json)).then(response => {
                    const dataSource = [...this.dataSource]
                    const target = dataSource.find(item => item.key === key)
                    if (target) {
                        target[dataIndex] = value
                        this.dataSource = dataSource
                    }
                }).catch(e => {
                    this.notifyError('Error', e.message);
                })
            },
            onDelete(record, key) {
                let resource = Object.assign({}, record);
                const dataSource = [...this.dataSource];
                this.dataSource = dataSource.filter(item => item.key !== key);
                this.count--;

                axios.delete('/' + this.resource + '/' + key).then(response => {
                }).catch(e => {
                    this.notifyError('Error', e.message);
                    const {count, dataSource} = this
                    const newData = resource;
                    this.dataSource = [newData, ...dataSource];
                    this.count = count + 1;
                })
            },
            create() {
                this.isLoading = true;
                let errors = {exist: false, field: ''};
                if (!this.newLink.checked_url.length) errors = {exist: true, field: 'Checked URL'};
                else if (!this.newLink.presence_url.length) errors = {exist: true, field: 'Presence URL'};

                if (errors.exist) {
                    this.isLoading = false;
                    return this.notifyError('Warning', 'The field ' + errors.field + ' should not be empty.');
                }

                axios.post('/' + this.resource, this.newLink).then(response => {
                    const {count, dataSource} = this;
                    const newData = Object.assign({}, this.newLink);
                    newData.key = newData.id = response.data.id;
                    newData.presence_url_added = this.getDate(response.data.presence_url_added);
                    this.dataSource = [newData, ...dataSource];
                    this.count = count + 1;
                    this.isLoading = false;
                    this.newLink = {checked_url: '', presence_url: '', email: '', details: '', active: 1, checked_url_status_code: '',
                        presence_url_status_code: '',
                        presence_url_added: '',
                        presence_url_removed: '',
                        last_check: '',};
                }).catch(e => {
                    this.notifyError('Error', e.message);
                    this.isLoading = false;
                })
            },
            changePage(page){
                document.getElementById('js-busy-loader').classList.remove("hidden");
                axios.get('/link?page=' + page).then(response => {
                    let data = response.data;
                    this.elements.current_page = data.current_page;
                    this.elements.last_page = data.last_page;
                    for (let k in data.data) {
                        if (data.data.hasOwnProperty(k)) {
                            data.data[k].key = data.data[k].id;
                        }
                    }
                    this.elements.data = data.data;
                    this.dataSource = data.data;
                    document.getElementById('js-busy-loader').classList.add("hidden");
                }).catch(e => {
                    this.notifyError('Error', e.message);
                    document.getElementById('js-busy-loader').classList.add("hidden");
                });
            },
            getDate(date) {
                let user_timezone = this.user_timezone;
                console.log('Given user_timezone: ');
                console.log(user_timezone);
                console.log('Given date: ');
                console.log(date);
                let copiedDate = new Date(date);
                console.log('Date: ' + copiedDate);
                copiedDate.setHours(copiedDate.getHours() + ((24 * user_timezone[1] + user_timezone[2]) * (user_timezone[0] == '+' ? 1 : -1)));
                console.log('Given copiedDate: ');
                console.log(copiedDate);
                return copiedDate.toLocaleString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true });
            }
        }
    }
</script>

<style>
    .editable-cell {
        position: relative;
    }

    .editable-cell-input-wrapper,
    .editable-cell-text-wrapper {
        padding-right: 24px;
    }

    .editable-cell-text-wrapper {
        padding: 5px 24px 5px 5px;
    }

    .editable-cell-icon,
    .editable-cell-icon-check {
        position: absolute;
        right: 0;
        width: 20px;
        cursor: pointer;
    }

    .editable-cell-icon {
        line-height: 18px;
        display: none;
    }

    .editable-cell-icon-check {
        line-height: 28px;
    }

    .editable-cell:hover .editable-cell-icon {
        display: inline-block;
    }

    .editable-cell-icon:hover,
    .editable-cell-icon-check:hover {
        color: #108ee9;
    }

    .editable-add-btn {
        margin-bottom: 8px;
    }

</style>

