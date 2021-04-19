/**
 * BhattiDhara
 * Copyright (C) 2021 BhattiDhara
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html
 *
 * @category BhattiDhara
 * @package Mage2_ProductDocs
 * @copyright Copyright (c) 2021 BhattiDhara
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author BhattiDhara
 */

define(['uiComponent', 'jquery', 'ko'], function(Component, $, ko) {
    'use strict';

    return Component.extend({
        defaults: {
            result: ko.observableArray(),
            isEnabled: ko.observable(false)
        },

        initialize: function () {
            this._super();

            var self = this;

            const query = `
{
  productDocs(product_id: ` + this.currentProductId + `, store_id: ` + this.currentStoreId + `) {
    totalCount
    items {
      document_id
      document_label
      file_name
    }
  }
}
`;
            const payload = {
                query: query,
                variables: {
                    product_id: 1,
                    store_id: 1
                }
            };

            $.ajax({
                url: 'graphql',
                contentType: 'application/json',
                dataType: 'json',
                method: 'POST',
                data: JSON.stringify(payload),
                success: (function (response) {
                    self.result(response.data);

                    if (self.result().productDocs.totalCount > 0) {
                        self.isEnabled(true);
                    }
                }),
                error: (function (error) {
                    console.log(error);
                })
            });
            return false;
        },

        getDocumentUrl: function (fileName) {
            return this.mediaPath + fileName;
        }
    });
});
