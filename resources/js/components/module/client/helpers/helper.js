export const getClientWithBalance = async (client_id) => {
    let data = {}
    await axios["post"](`/cliente/get-client-with-balance/${client_id}`, {}).then((response) => {
        data = response.data;
    });
    return data;
}

export const getClientTickets = async (client_id) => {
    let data = {}
    await axios["post"](`/cliente/get-tickets-open/${client_id}`, {}).then((response) => {
        data = response.data;
    });
    return data;
}

export const hasService = async (client_id, service) => {
    let data = {}
    await axios["post"](`/cliente/has-service/${client_id}`, {service}).then((response) => {
        data = response.data;
    });
    return data;
}

export const getPlansForByBundleId = async (bundle_id, idClient) => {
    let data = {}
    await axios["post"](`/cliente/clientbundleservice/bundle/${bundle_id}`, {idClient}).then((response) => {
        data = response.data;
    });
    return data;
}

export const requestEditedBundlePlan = async (service_bundle_id) => {
    let data = {}
    await axios["post"](`/cliente/clientbundleservice/bundle/edit/${service_bundle_id}`, {}).then((response) => {
        data = response.data;
    });
    return data;
}

export const requestGetClientStatus = async (client_id) => {
    let data = {}
    await axios["post"](`/cliente/get-client-status/${client_id}`, {}).then((response) => {
        data = response.data;
    });
    return data;
}

export const canAddService = async (client_id, service) => {
    let data = {}
    await axios["post"](`/cliente/can-add-service/${client_id}`, {service}).then((response) => {
        data = response.data;
    });
    return data;
}

