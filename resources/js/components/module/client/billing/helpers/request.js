export const payments = async (client_id) => {
    let data = {}
    await axios["post"](`/cliente/billing/payment/get-totals/${client_id}`, {}).then((response) => {
        data = response.data;
    });
    return data;
}

export const transactions = async (client_id) => {
    let data = {}
    await axios["post"](`/cliente/billing/transaction/get-totals/${client_id}`, {}).then((response) => {
        data = response.data;
    });
    return data;
}

export const getCostAllServiceActive = async (client_id) => {
    let data = {}
    await axios["post"](`/cliente/billing/payment/get-cost-all-service-active/${client_id}`, {}).then((response) => {
        data = response.data;
    });
    return data;
}

export const getActiveServiceExpiration = async (client_id) => {
    let data = {}
    await axios["post"](`/cliente/billing/payment/get-active-service-expiration/${client_id}`, {}).then((response) => {
        data = response.data;
    });
    return data;
}

export const getEventByDate = async (postData) => {
    let data = {}
    await axios["post"](`/fullcalendar/get-billing-configuration`, {postData}).then((response) => {
        data = response.data;
    });
    return data;
}

export const requestBillingInformationBlock = async (client_id) => {
    let data = {}
    await axios["post"](`/cliente/billing/get-billing-information-block/${client_id}`, {}).then((response) => {
        data = response.data;
    });
    return data;
}

export const getClientDebit = async (client_id) => {
    let data = {}
    await axios["post"](`/cliente/debit/${client_id}`, {}).then((response) => {
        data = response.data;
    });
    return data;
}

export const requestPaymentMethod = async (payment_method_id) => {
    let data = {}
    await axios["post"](`/cliente/billing/get-payment-method/${payment_method_id}`, {}).then((response) => {
        data = response.data;
    });
    return data;
}


