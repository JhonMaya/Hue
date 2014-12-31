<?php exit() ?>--by CLG Aphromoo 70.193.130.15
if myHero.charName ~= 'Ahri' then return end

function OnTick()
	p = CLoLPacket(0x9E)
	p.dwArg1 = 1
	p.dwArg2 = 0
	p:EncodeF(myHero.networkID)
	RecvPacket(p)
	p = CLoLPacket(0x2E)
	p.dwArg1 = 1
	p.dwArg2 = 0
	p:EncodeF(myHero.networkID)
	RecvPacket(p)
end
