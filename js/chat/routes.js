// This file is required by app.js. It sets up event listeners
// for the two main URL endpoints of the application - /create and /chat/:id
// and listens for socket.io messages.

// Use the gravatar module, to turn email addresses into avatar images:

var gravatar = require('gravatar');

//var Sequelize = require('sequelize');
//var sequelize = new Sequelize('mysql.guiasjp.com', 'username', 'password');

// Export a function, so that we can pass 
// the app and io instances from the app.js file:

module.exports = function(app,io){

	app.get('/:id_cliente', function(req, res){

		// Render views/home.html
		// Generate unique id for the room
		var id = Math.round((Math.random() * 1000000));
                console.log(res);
		// Redirect to the random room
//		res.redirect('/chat/'+id+'/');
	});

	app.get('/chat/:id/:id_cliente', function(req,res){

		// Render the chant.html view
		res.render('chat');
	});

	// Initialize a new socket.io application, named 'chat'
	var chat = io.on('connection', function (socket) {

		// When the client emits the 'load' event, reply with the 
		// number of people in this chat room

		socket.on('load',function(data){
                    var room = findClientsSocket(io,data);
                    console.log(room);
                    if(room.length === 0 ) 
                    {
                        socket.emit('conectou', {quantidade : 0});
                    }
                    else if(room.length === 1) {
                        socket.emit('conectou', {
                            quantidade: 1,
                            user: room[0].username,
//					avatar: room[0].avatar,
                            id: data
                        });
                    }
                    else if(room.length >= 2)
                    {
                        chat.emit('tooMany', {boolean: true});
                    }
		});

		// When the client emits 'login', save his name and avatar,
		// and add them to the room

		// Somebody left the chat
		socket.on('disconnect', function() {

			// Notify the other person in the chat room
			// that his partner has left

			socket.broadcast.to(this.room).emit('leave', {
				boolean: true,
				room: this.room,
				user: this.username,
				avatar: this.avatar
			});

			// leave the room
			socket.leave(socket.room);
		});


		// Handle the sending of messages
		socket.on('msg', function(data){
                        console.log(data.msg);
                        console.log(socket.room);
			// When the server receives a message, it sends it to the other person in the room.
			socket.broadcast.to(socket.room).emit('receive', {msg: data.msg, user: data.user, img: data.img});
		});
                
                //Quando um usuário estiver digitando, apareece "...digitando"
                socket.on('typing',function(data){
                    socket.broadcast.to(socket.room).emit('typing',{user : data.user});
                });
                
                socket.on('stop_typing',function(data){
                    socket.broadcast.to(socket.room).emit('stop_typing',{user : data.user});
                });
	});
};

function findClientsSocket(io,roomId, namespace) {
	var res = [],
		ns = io.of(namespace ||"/");    // the default namespace is "/"

	if (ns) {
		for (var id in ns.connected) {
			if(roomId) {
				var index = ns.connected[id].rooms.indexOf(roomId) ;
				if(index !== -1) {
					res.push(ns.connected[id]);
				}
			}
			else {
				res.push(ns.connected[id]);
			}
		}
	}
	return res;
}


