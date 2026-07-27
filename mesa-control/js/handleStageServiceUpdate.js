const modalStageServiceUpdate = new bootstrap.Modal(document.getElementById('stageServicestatusUpdateModal'));

export const handleStageServiceUpdate = async(trigger, elements) => {
    const serviceId = trigger.dataset.stageServiceServiceId;
    modalStageServiceUpdate.show();

    console.log(serviceId)
}