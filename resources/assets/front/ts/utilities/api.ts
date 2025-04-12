import axios from "axios";
import * as _ from "lodash";

const get = async (url: string): Promise<any> => {
    return await request("get", url);
};

const post = async (url: string, values?: any, config?: any): Promise<any> => {
    return await request("post", url, values, config);
};

const put = async (url: string, values?: any, config?: any): Promise<any> => {
    return await request("put", url, values, config);
};

const del = async (url: string, values?: any, config?: any): Promise<any> => {
    return await request("delete", url, values, config);
};

const request = async (
    method: string,
    url: string,
    values?: any,
    config?: any
): Promise<any> => {
    // console.log('Request:%s', url);
    const response = await axios[method](
        url,
        jsonToForm(values, new FormData()),
        config
    ).catch((error: any) => {
        throw error;
    });
    // console.log("Response:%s", JSON.stringify(response));
    return response.data;
};

const jsonToForm = (params: any[], formData: FormData, name = "") => {
    if (_.isArray(params)) formatArray(params, formData, name);
    if (_.isPlainObject(params)) formatObject(params, formData, name);
    return formData;
};

const formatObject = (params: any, formData: any, name: string) => {
    _.forEach(params, (v, k) => {
        if (_.isArray(v) || _.isPlainObject(v)) {
            jsonToForm(v, formData, !name ? k : `${name}.${k}`);
            return;
        }
        formData.append(!name ? k : `${name}.${k}`, v);
    });
};

const formatArray = (params: any[], formData: any, name: string) => {
    params.map((data, index) => {
        if (_.isArray(data) || _.isPlainObject(data)) {
            jsonToForm(data, formData, `${name}[${index}]`);
            return;
        }
        formData.append(`${name}[${index}]`, data);
    });
    return formData;
};

export const API = {
    get,
    post,
    put,
    del,
};
