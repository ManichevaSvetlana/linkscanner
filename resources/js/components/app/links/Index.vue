<template>
    <div>

        <links-table :columns="columns" :data="dataSource" :resource="'links'" :user_timezone="user_timezone"></links-table>


    </div>
</template>
<script>


    export default {
        props: ['resources'],

        data() {
            return {
                dataSource: this.resources,
                columns: [
                    {
                        field_type: 'text',
                        editable: true,
                        title: 'Checked URL',
                        dataIndex: 'checked_url',
                        key: '1',
                        width: 150,
                        scopedSlots: {customRender: 'checked_url'},
                    },
                    {
                        field_type: 'text',
                        editable: true,
                        title: 'Presence URL',
                        dataIndex: 'presence_url',
                        key: '2',
                        width: 150,
                        scopedSlots: {customRender: 'presence_url'},
                    },

                    {
                        title: 'Checked URL Status Code',
                        dataIndex: 'checked_url_status_code',
                        key: '3',
                        width: 150,
                        tag: true,
                        scopedSlots: {customRender: 'checked_url_status_code'},
                    },
                    {
                        title: 'Presence URL Status Code',
                        dataIndex: 'presence_url_status_code',
                        key: '4',
                        width: 150,
                        tag: true,
                        scopedSlots: {customRender: 'presence_url_status_code'},
                    },
                    {
                        title: 'Presence URL Added',
                        dataIndex: 'presence_url_added',
                        key: '5',
                        width: 150,
                        scopedSlots: {customRender: 'presence_url_added'},
                    },
                    {
                        title: 'Presence URL Removed',
                        dataIndex: 'presence_url_removed',
                        key: '6',
                        width: 150,
                        scopedSlots: {customRender: 'presence_url_removed'},
                    },
                    {
                        title: 'Last Check',
                        dataIndex: 'last_check',
                        key: '7',
                        width: 150,
                        scopedSlots: {customRender: 'last_check'},
                    },

                    {
                        field_type: 'text',
                        editable: true,
                        title: 'Email',
                        dataIndex: 'email',
                        key: '8',
                        width: 150,
                        scopedSlots: {customRender: 'email'},
                    },
                    {
                        field_type: 'textarea',
                        editable: true,
                        title: 'Details',
                        dataIndex: 'details',
                        key: '9',
                        width: 150,
                        scopedSlots: {customRender: 'details'},
                    },
                    {
                        field_type: 'checkbox',
                        editable: true,
                        title: 'Active',
                        dataIndex: 'active',
                        key: '10',
                        width: 150,
                        scopedSlots: {customRender: 'active'},
                    },
                    {
                        title: 'Action',
                        key: 'operation',
                        fixed: 'right',
                        width: 100,
                        scopedSlots: {customRender: 'operation'},
                    },
                ],
                user_timezone: ''
            }
        },
        created() {
            let date = new Date().toString();
            console.log(date);
            date = date.split(' ')[5].replace('data-', '').substring(3);
            console.log(date);
            this.user_timezone = date.toString();
            
            console.log(this.resources);
            for (let k in this.dataSource['data']) {
                if (this.dataSource['data'].hasOwnProperty(k)) {
                    this.dataSource['data'][k].key = this.dataSource['data'][k].id;
                    if (this.dataSource['data'][k].presence_url_added && this.dataSource['data'][k].presence_url_added.length) this.dataSource['data'][k].presence_url_added = this.getDate(this.dataSource['data'][k].presence_url_added);
                    if (this.dataSource['data'][k].presence_url_removed && this.dataSource['data'][k].presence_url_removed.length) this.dataSource['data'][k].presence_url_removed = this.getDate(this.dataSource['data'][k].presence_url_removed);
                    if (this.dataSource['data'][k].last_check && this.dataSource['data'][k].last_check.length) this.dataSource['data'][k].last_check = this.getDate(this.dataSource['data'][k].last_check);

                }
            }
            
        },
        beforeMount() {

        },
        methods: {
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

