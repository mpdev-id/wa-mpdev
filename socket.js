let io;

export const setIo = (socketIoInstance) => {
    io = socketIoInstance;
};

export const getIo = () => {
    return io;
};
