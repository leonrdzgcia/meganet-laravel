<template>
    <a :href="`/tickets/notifica/${data.id}`"  class="text-reset notification-item">
        <div class="d-flex">
            <div class="flex-shrink-0 me-3">
                <img src="/assets/images/users/avatar-3.jpg"
                     class="rounded-circle avatar-sm"
                     alt="user-pic">
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-1">{{ data.reported_by }}</h6>
                <div class="font-size-13 text-muted">
                    <p class="mb-1">{{ data.topic }}</p>
                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>{{ data.created_at }}</span>
                    </p>
                </div>
            </div>
        </div>
    </a>
</template>


<script>
import {ref} from 'vue'
export default {
    name: "NotificationTopbar",
    props:{
       notification: String,
    },
    setup(props){
        const data = ref(JSON.parse(props.notification))
        return {
            data
        }
    }
}
</script>
