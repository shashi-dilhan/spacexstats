define(['jquery', 'knockout', 'ko.mapping'], function($, ko, koMapping) {

    var ObjectViewModel = function(object_id) {

        var getOriginalValue = ko.bindingHandlers.value.init;
        ko.bindingHandlers.value.init = function(element, valueAccessor, allBindings) {
            if (allBindings.has('getOriginalValue')) {
                valueAccessor()(element.value);
            }
            getOriginalValue.apply(this, arguments);
        };

        var self = this;

        self.note = ko.observable();

        self.noteButtonText = ko.computed({ read: function() {
            if (self.note() == "") {
                return 'Create Note';
            } else {
                return 'Edit Note';
            }
        }, deferEvaluation: true });

        self.noteReadText = ko.computed({ read: function() {
            if (self.note() == "") {
                return 'Create a Note!';
            }  else {
                return self.note();
            }
        }, deferEvaluation: true });

        self.noteState = ko.observable('read');

        self.changeNoteState = function() {
            if (self.noteState() == 'read') {
                self.originalNote = self.note();
                self.noteState('write');
            } else {
                self.noteState('read');
            }

        };

        self.saveNote = function() {
            if (self.originalNote == "") {
                var requestType = 'POST';
            } else {
                var requestType = 'PATCH';
            }

            $.ajax('/missioncontrol/objects/' + object_id + '/note', {
                type: requestType,
                data: { note: self.note() },
                success: function(response) {
                    self.changeNoteState();
                }
            });
        };

        self.deleteNote = function() {
            $.ajax('/missioncontrol/objects/' + object_id + '/note', {
                type: 'DELETE',
                success: function(response) {
                    self.note("");
                    self.changeNoteState();
                }
            });
        }

        self.toggleFavorite = function() {
            $.ajax('/missioncontrol/object/' + object_id + '/favorite', {
                type: 'POST',
                success: function(response) {

                }
            });
        };

        self.download = function() {

        };
    };

    return ObjectViewModel;
});
