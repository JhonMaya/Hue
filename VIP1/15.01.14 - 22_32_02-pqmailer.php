<?php exit() ?>--by pqmailer 217.82.39.147
_G.CastSpell = function(spell, t1,t2)
if t2 == nil and t1 ~= nil then
	pE = CLoLPacket(0x99)
	pE:EncodeF(myHero.networkID)
	pE:Encode1(spell)
	pE:EncodeF(myHero.x)
	pE:EncodeF(myHero.z)
	pE:EncodeF(0)
	pE:EncodeF(0)
	if tar then
		pE:EncodeF(tar.networkID)
	end
	pE.dwArg1 = 1
	pE.dwArg2 = 0
	SendPacket(pE)
elseif t1 == nil and t2 == nil then
	pE = CLoLPacket(0x99)
	pE:EncodeF(myHero.networkID)
	pE:Encode1(spell)
	pE:EncodeF(myHero.x)
	pE:EncodeF(myHero.z)
	pE:EncodeF(0)
	pE:EncodeF(0)
	pE:EncodeF(0)
	pE.dwArg1 = 1
	pE.dwArg2 = 0
	SendPacket(pE)
else
	pE = CLoLPacket(0x99)
	pE:EncodeF(myHero.networkID)
	pE:Encode1(spell)
	pE:EncodeF(myHero.x)
	pE:EncodeF(myHero.z)
	pE:EncodeF(t1)
	pE:EncodeF(t2)
	pE:EncodeF(0)
	pE.dwArg1 = 1
	pE.dwArg2 = 0
	SendPacket(pE)
end