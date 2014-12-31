<?php exit() ?>--by CLG Aphromoo 70.193.128.236
function OnLoad()
	PrintChat("<font color = '#00FFFF'> >> Riven Q Canceller v0.01 by Trees</font>")
end

function OnSendPacket(p)
	if p.header == Packet.headers.S_CAST then
		local decodedPacket = Packet(p)
		if decodedPacket:get('spellId') == _Q then Emote() end
	end
end

function Emote()
	p = CLoLPacket(0x47)
	p:EncodeF(myHero.networkID)
	p:Encode1(2)
	p.dwArg1 = 1
	p.dwArg2 = 0
	SendPacket(p)
end