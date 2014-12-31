<?php exit() ?>--by ikita 108.162.222.79
target = player

function CastPacket2(i)
	pE = CLoLPacket(0x9A)
	pE:EncodeF(target.networkID)
	pE:Encode1(i)
	pE:EncodeF(target.x)
	pE:EncodeF(target.z)
	pE:EncodeF(mousePos.x)
	pE:EncodeF(mousePos.z)
	pE:EncodeF(0)
	pE.dwArg1 = 1
	pE.dwArg2 = 0
	SendPacket(pE)
end

function CastPacket(i)
	pE = CLoLPacket(56)
	pE:EncodeF(target.networkID)
	pE:Encode1(i)
	pE:Encode2(0)
	pE.dwArg1 = 1
	pE.dwArg2 = 0
	SendPacket(pE)
end

function OnTick()
	if target.networkID ~= player.networkID then
		CastPacket(0)
	end
	for i = 0, objManager.maxObjects, 1 do
		local object = objManager:GetObject(i)
		if object ~= nil and (object.name == "Tibbers" or object.name == player.name) and object.type == "obj_AI_Minion" then
			target = object
		end
	end
end

function OnDraw()
	DrawCircle(target.x,target.y,target.z,200,0x0000FF)
end