<?php exit() ?>--by ragequit 71.207.146.17

local consumables = { [2003] = 1, [2004] = 1, [2010] = 1, [2037] = 1, [2039] = 1, [2040] = 1, [2043] = 1, [2044] = 1, [2047] = 1, [2048] = 1}
local expitem = {[3153] = 1, [3077] =1 }
local blockSell = false
local fake = {}
local debug = false
local sold = false
local recv = false
local target1
local function PrintChat(arg, ov)
	if not debug and not ov then return end
	_G.PrintChat(tostring(arg))
end


function OnLoad()
	PrintChat('l')
	Menu = scriptConfig('Sell Exploiter', 'SellExploiter.cfg')
	--Menu:addParam('list', 'Sell', SCRIPT_PARAM_LIST, 1, { 'Sell Item', 'Sell Item 1', 'Sell Item 2', 'Sell Item 3', 'Sell Item 4', 'Sell Item 5', 'Sell Item 6', 'Sell Trinket' })
	--Menu:permaShow('list')
	Menu:addParam('I1', 'Sell Item 1', SCRIPT_PARAM_ONOFF, false)
	Menu:addParam('I2', 'Sell Item 2', SCRIPT_PARAM_ONOFF, false)
	Menu:addParam('I3', 'Sell Item 3', SCRIPT_PARAM_ONOFF, false)
	Menu:addParam('I4', 'Sell Item 4', SCRIPT_PARAM_ONOFF, false)
	Menu:addParam('I5', 'Sell Item 5', SCRIPT_PARAM_ONOFF, false)
	Menu:addParam('I6', 'Sell Item 6', SCRIPT_PARAM_ONOFF, false)
	Menu:addParam('I7', 'Sell Trinket', SCRIPT_PARAM_ONOFF, false)
--	Menu:addParam('ex7', 'Exploit Trinket', SCRIPT_PARAM_ONOFF, false)
	Menu:addParam('sell', 'Sell Exploit', SCRIPT_PARAM_ONOFF, false)
	Menu:addParam('sell2', 'Sell All', SCRIPT_PARAM_ONOFF, false)
 -- Menu:addParam('recv', 'RECV 6E Recall', SCRIPT_PARAM_ONKEYDOWN, false, GetKey('R'))
	Menu.I1 = false
	Menu.I2 = false
	Menu.I3 = false
	Menu.I4 = false
	Menu.I5 = false
	Menu.I6 = false
	Menu.I7 = false
	Menu.sell = false
	Menu.sell2 = false

end

function OnTick()
	for i = 1, 7 do
	if GetItem( i + 3) == nil then fake[ i - 1] = false end
		if Menu['I' .. i] then 
			recvSell( i - 1 ) 
			Menu['I' .. i ] = false
		end
	end
	
	if Menu.sell2 and not sold then
		sold = true
		PrintChat('start', true)
		for i = 0, 10 do
			for j = -2000, 2000 do Sell(j, true, GetTarget().networkID) end
		end
	--	Sell(7, false)
		Menu.sell2 = false
		PrintChat('done', true)
	end
	
	if Menu.recv and not recv then
		recv = true
		Menu.recv = false
		p = CLoLPacket(0x6E)
		p:EncodeF(myHero.networkID)
		p:Encode2(2003)
		p:Encode2(0)
		p:Encode1(3)
		p:Encode1(1)
		p:Encode1(0)
		--p:Encode1(64)
		RecvPacket(p)
	end
	  if IsKeyPressed(GetKey("V")) then
                for i, v in pairs(GetEnemyHeroes()) do
                        if ValidTarget(v, 650) then
                                Packet('S_CAST', { spellId = 10 , targetNetworkId = v.networkID }):send()
                        end
                end
        end
	end
end

function OnSendPacket(p)
	if p.header == 0x99 then
		local decodedPacket = Packet(p)
		local spellId1 = decodedPacket:get('spellId')
		target1 = decodedPacket:get('targetNetworkId')
		if ((spellId1 >= 4 and spellId1 <= 9) or (spellId1 == 10 and Menu.ex7)) and consumables[GetItem(spellId1).id] ~= nil and not GetInventorySlotIsEmpty(spellId1) and not fake[spellId1 - 4] then
			PrintChat('block')
			p:Block()
			Sell(spellId1 - 4)
			Packet('S_CAST', { spellId = spellId1  }):send()
		end
		if (spellId1 >= 4 and spellId1 <= 10) and expitem[GetItem(spellId1).id] ~= nil and not GetInventorySlotIsEmpty(spellId1) and not fake[spellId1 - 4] then
			p:Block()
			Sell(spellId1 - 4)
			fromslot = spellId1 - 4
			swap(fromslot,6)
			Packet('S_CAST', { spellId = 10 , targetNetworkId = target1 }):send()
		end	
--	elseif p.header == 0x09 then PrintChat('b') p:Block()
	end
end

function swap(a,b)
	p = CLoLPacket(0x20)
	p.dwArg1 = 1
	p.dwArg2 = 0
	p:EncodeF(myHero.networkID)
	p:Encode1(a)
	p:Encode1(b)
	SendPacket(p)
end

 

function Sell(slot, t, id)
	local id1 = id and id or myHero.networkID
	if not t then Menu.sell = true end
	p = CLoLPacket(0x09)
	p.dwArg1 = 1
	p.dwArg2 = 0
	p:EncodeF(id1)
	--p:Encode1(slot+128)
	p:Encode1(slot)
	SendPacket(p)
end

function recvSell(slot)
	if fake[slot] ~= true then PrintChat('isnt fake!') return end
	p = CLoLPacket(0x0B)
	p:EncodeF(myHero.networkID)
	p:Encode1(slot)
	p:Encode1(0)
	RecvPacket(p)
	fake[slot] = false
	PrintChat(tostring(slot) .. ' is gone')
end

function OnRecvPacket(p)
	if p.header == 0x0B and Menu.sell then 
		--local decodedPacket = Packet(p)
		p.pos = 5
		local slot = p:Decode1() - 128
		p:Block()
		Menu.sell = false
		fake[slot] = true
		PrintChat(tostring(slot) .. ' is fake!')
		--fake[decodedPacket:get('slot')] = true
	--	PrintChat(tostring(decodedPacket:get('slot')) .. ' is fake!')
	end
end

function OnSendChat(msg)
	PrintChat(msg)
	if not msg or msg:sub(1, 2) ~= ".s" then return end
	BlockChat()
	local slot = msg:sub(3)
	if type(slot) == 'number' then recvSell(slot-1) end
end